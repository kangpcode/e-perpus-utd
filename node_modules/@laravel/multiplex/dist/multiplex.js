import { jsx as _jsx } from "react/jsx-runtime";
import { existsSync, statSync } from "node:fs";
import { constants } from "node:os";
import { resolve } from "node:path";
import { render } from "ink";
import { App } from "./app.js";
import { normalizeCommands } from "./args.js";
import { runInline } from "./inline.js";
import { fitsTui, formatStreamContinuation, formatStreamLabel, inlineChildColumns, MIN_COLUMNS, MIN_ROWS, sanitizeTitle, systemMsg, } from "./util.js";
export { DEFAULT_COLORS } from "./color.js";
const SIGNALS = ["SIGINT", "SIGTERM", "SIGHUP", "SIGQUIT"];
function positiveInt(value, name) {
    if (!Number.isSafeInteger(value) || value <= 0) {
        throw new Error(`${name} must be a positive integer, got ${value}.`);
    }
    return value;
}
function supportsColor() {
    if (process.env.NO_COLOR) {
        return false;
    }
    if (process.env.FORCE_COLOR && process.env.FORCE_COLOR !== "0") {
        return true;
    }
    return Boolean(process.stdout.isTTY);
}
/**
 * Whether stdin will actually enter raw mode, which is not the same question as
 * `isTTY`. A terminal we do not own — a background process group, a pty whose
 * other end has gone, a `/dev/tty` handed to us by a parent that kept the
 * foreground for itself — reports itself as a TTY and then fails the ioctl with
 * EIO. Node reports that by emitting "error" on the stream rather than throwing,
 * so a bare try/catch never sees it and the failure surfaces as an uncaught
 * error from inside Ink's mount. Probing once, before anything is drawn, lets a
 * terminal that cannot do raw mode fall back to inline output like any other
 * terminal we cannot draw in.
 */
function rawModeUsable() {
    const stdin = process.stdin;
    if (typeof stdin.setRawMode !== "function") {
        return false;
    }
    // The probe's own failure has to be listened for, or it is the same
    // uncaught "error" event we are here to avoid.
    let emitted = false;
    const onError = () => {
        emitted = true;
    };
    const wasRaw = stdin.isRaw === true;
    stdin.on("error", onError);
    try {
        stdin.setRawMode(true);
        // `isRaw` only moves when the ioctl succeeded, so it answers both
        // questions: whether we got raw mode, and whether there is anything to
        // undo. The terminal goes back the way we found it either way — a
        // failed probe means inline mode, and inline mode wants the terminal it
        // started with, echo and Ctrl-C included.
        const entered = !emitted && stdin.isRaw === true;
        if (stdin.isRaw !== wasRaw) {
            stdin.setRawMode(wasRaw);
        }
        return entered;
    }
    catch {
        return false;
    }
    finally {
        stdin.removeListener("error", onError);
    }
}
/**
 * Installs the teardown that has to survive a signal. `process.on("exit")` does
 * not run when we are killed by one, and the children sit in their own process
 * groups so they never see the terminal's own SIGHUP — without these, closing
 * the window leaves every dev server running and holding its port.
 */
function installTeardown(shutdown, onExit) {
    const handlers = SIGNALS.map((signal) => {
        // Waits for the shutdown so the children get their chance to clean up,
        // which is only safe because a second signal re-enters here, finds the
        // shutdown already running and exits through onExit — SIGKILL and no
        // waiting. Someone who wants out now presses it again.
        const handler = () => {
            shutdown().finally(() => {
                process.exit(128 + constants.signals[signal]);
            });
        };
        process.on(signal, handler);
        return [signal, handler];
    });
    process.on("exit", onExit);
    return () => {
        for (const [signal, handler] of handlers) {
            process.removeListener(signal, handler);
        }
        process.removeListener("exit", onExit);
    };
}
/**
 * Runs the commands and resolves with an exit code once everything has exited,
 * the terminal is restored and every child process is gone. Options are
 * validated before we touch the terminal, so a bad option throws with the screen
 * untouched.
 *
 * Renders the TUI when the terminal can support it and falls back to inline
 * output — every line printed as it arrives, no alternate screen, no input —
 * when it cannot, so a pipe, a CI job, a window too small to draw a layout in or
 * a terminal that will not give us raw mode gets usable output rather than an
 * error.
 */
export async function multiplex(options) {
    const commandDefs = normalizeCommands(options.commands ?? []);
    const bufferSize = positiveInt(options.bufferSize ?? 2_000, "bufferSize");
    const streamBufferSize = positiveInt(options.streamBufferSize ?? 10_000, "streamBufferSize");
    const timestamps = options.timestamps ?? false;
    const title = options.title ? sanitizeTitle(options.title) : undefined;
    const json = options.json ?? false;
    // Nothing below has to be answered for a run that was never going to draw a
    // TUI, and `rawModeUsable` is the one that costs a terminal side effect.
    const wantsTui = !json && !(options.inline ?? false);
    const hasTty = Boolean(process.stdin.isTTY && process.stdout.isTTY);
    const noRawMode = wantsTui && hasTty && !rawModeUsable();
    const interactive = hasTty && !noRawMode;
    const columns = process.stdout.columns ?? 0;
    const rows = process.stdout.rows ?? 0;
    const tooSmall = wantsTui && interactive && !fitsTui(columns, rows);
    const inline = json || (options.inline ?? false) || !interactive || tooSmall;
    const cwd = resolve(options.cwd ?? process.cwd());
    if (!existsSync(cwd)) {
        throw new Error(`cwd path does not exist: ${cwd}`);
    }
    if (!statSync(cwd).isDirectory()) {
        throw new Error(`cwd path is not a directory: ${cwd}`);
    }
    const supervisorRef = { current: null };
    /** SIGTERM, a grace period, then SIGKILL for anything still standing. */
    function terminate() {
        return supervisorRef.current?.terminate() ?? Promise.resolve();
    }
    /** The synchronous last resort, for the paths that cannot wait. */
    function killAll() {
        supervisorRef.current?.killAll();
    }
    return inline ? runInlineMode() : runTui();
    async function runInlineMode() {
        const color = !json && supportsColor();
        const useTitle = title && !json && process.stdout.isTTY;
        let shuttingDown = false;
        function restoreTitle() {
            if (!useTitle) {
                return;
            }
            try {
                process.stdout.write("\x1b[23;0t");
            }
            catch {
                //
            }
        }
        async function shutdown() {
            if (shuttingDown) {
                return;
            }
            shuttingDown = true;
            await terminate();
            restoreTitle();
        }
        const uninstall = installTeardown(shutdown, () => {
            killAll();
            restoreTitle();
        });
        // Without a TTY inline mode is the expected outcome and needs no
        // explanation, and neither does one that was asked for; in a real
        // terminal the missing TUI does.
        const notice = tooSmall
            ? `Terminal is ${columns}x${rows}; the TUI needs at least ${MIN_COLUMNS}x${MIN_ROWS}. Running inline.`
            : noRawMode
                ? "Terminal will not enter raw mode, so the TUI has no keyboard input. Running inline."
                : undefined;
        if (notice) {
            process.stderr.write(`${color ? systemMsg(notice) : notice}\n`);
        }
        if (useTitle) {
            process.stdout.write(`\x1b[22;0t\x1b]0;${title}\x07`);
        }
        try {
            const { done } = runInline({
                commandDefs,
                cwd,
                timestamps,
                autoRestart: options.restart ?? true,
                json,
                color,
                columns: inlineChildColumns(commandDefs.map((c) => c.label), process.stdout.columns ?? 80, timestamps),
                supervisorRef,
            });
            const code = await done;
            await shutdown();
            return code;
        }
        catch {
            await shutdown();
            return 1;
        }
        finally {
            uninstall();
        }
    }
    async function runTui() {
        const outputRef = { current: [] };
        let instance;
        let shuttingDown = false;
        let titlePushed = false;
        function restoreTerminal() {
            try {
                process.stdout.write("\x1b[?25h\x1b[?1049l");
                // Guarded: restoreTerminal runs twice, and a second pop would take someone else's title off the stack.
                if (titlePushed) {
                    titlePushed = false;
                    process.stdout.write("\x1b[23;0t");
                }
            }
            catch {
                //
            }
        }
        function flushOutput() {
            if (outputRef.current.length === 0) {
                return;
            }
            const maxLabelLen = Math.max(...commandDefs.map((c) => c.label.length));
            try {
                for (const sl of outputRef.current) {
                    const cmd = commandDefs[sl.cmdIndex];
                    const prefix = sl.cont
                        ? formatStreamContinuation(maxLabelLen, true)
                        : formatStreamLabel(cmd.label, cmd.color, maxLabelLen, true);
                    process.stdout.write(`${sl.ts}${prefix}${sl.text}\n`);
                }
            }
            catch {
                // The terminal can already be gone when we got here via SIGHUP.
            }
        }
        /**
         * Unmount first, so Ink's final frame and its raw-mode teardown happen
         * while we still own the alternate screen, then leave the screen before
         * flushing so the logs land in the real scrollback. The wait for the
         * children sits between the two: they can still be printing, and the
         * flush should carry whatever they say on their way out, but nobody
         * should have to watch a dead frame while they take it.
         */
        async function shutdown() {
            if (shuttingDown) {
                return;
            }
            shuttingDown = true;
            try {
                instance?.unmount();
            }
            catch {
                //
            }
            restoreTerminal();
            await terminate();
            killAll();
            flushOutput();
        }
        const uninstall = installTeardown(shutdown, () => {
            killAll();
            restoreTerminal();
        });
        // Ink toggles raw mode on mount and again on unmount, and a stdin that
        // stops working in between reports it by emitting "error" rather than
        // throwing. Unhandled, that ends the process where it stands — mid-frame
        // or mid-shutdown, with the alternate screen still up and the children
        // still running. There is nothing useful to do about input that has gone
        // away while the TUI is up, so absorb it and let the teardown finish.
        const ignoreStdinError = () => { };
        process.stdin.on("error", ignoreStdinError);
        if (title) {
            process.stdout.write(`\x1b[22;0t\x1b]0;${title}\x07`);
            titlePushed = true;
        }
        process.stdout.write("\x1b[?1049h\x1b[?25l");
        try {
            instance = render(_jsx(App, { commandDefs: commandDefs, cwd: cwd, initialStreamMode: options.stream ?? false, bufferSize: bufferSize, streamBufferSize: streamBufferSize, timestamps: timestamps, autoRestart: options.restart ?? true, title: title, outputRef: outputRef, supervisorRef: supervisorRef }), { exitOnCtrlC: true });
            await instance.waitUntilExit();
            await shutdown();
            return 0;
        }
        catch {
            await shutdown();
            return 1;
        }
        finally {
            process.stdin.removeListener("error", ignoreStdinError);
            uninstall();
        }
    }
}
