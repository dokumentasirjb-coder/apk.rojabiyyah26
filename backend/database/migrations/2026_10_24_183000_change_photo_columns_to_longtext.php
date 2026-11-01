<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_items', function (Blueprint $table) {
            $table->longText('photo_in')->nullable()->change();
            $table->longText('photo_out')->nullable()->change();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->longText('photo_path')->nullable()->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->longText('photo_path')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('loan_items', function (Blueprint $table) {
            $table->string('photo_in', 255)->nullable()->change();
            $table->string('photo_out', 255)->nullable()->change();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('photo_path', 255)->nullable()->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->string('photo_path', 255)->nullable()->change();
        });
    }
};

