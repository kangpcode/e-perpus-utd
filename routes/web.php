<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — DIGIPUS Single Page Application (SPA)
|--------------------------------------------------------------------------
|
| Semua rute web (non-API) dialihkan ke aplikasi frontend DIGIPUS (Vue 3 SPA).
| Hal ini memastikan pengguna langsung melihat antarmuka Claymorphism
| DIGIPUS di route utama (/) dan bukan template bawaan Laravel.
|
*/

Route::get('/{any?}', function () {
    $spaIndex = public_path('index.html');
    if (File::exists($spaIndex)) {
        return response()->file($spaIndex, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    $distIndex = base_path('frontend/dist/index.html');
    if (File::exists($distIndex)) {
        return response()->file($distIndex, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'DIGIPUS SPA frontend belum dikompilasi. Jalankan "npm run build" pada direktori frontend/.'
    ], 503);
})->where('any', '^(?!api).*$');
