<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim_nidn')->nullable()->after('email')->index();
            $table->string('faculty')->nullable()->after('nim_nidn');
            $table->string('major')->nullable()->after('faculty');
            $table->string('phone')->nullable()->after('major');
            $table->string('avatar')->nullable()->after('phone');
            $table->unsignedInteger('max_borrow_quota')->default(5)->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim_nidn', 'faculty', 'major', 'phone', 'avatar', 'max_borrow_quota']);
        });
    }
};
