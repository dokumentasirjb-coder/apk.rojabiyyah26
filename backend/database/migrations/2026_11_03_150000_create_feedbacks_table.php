<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('category')->default('Umum'); // Proyektor, Dokumentasi, Acara, Sistem, Lainnya
            $table->text('message');
            $table->tinyInteger('rating')->nullable();
            $table->string('status')->default('unread'); // unread, read, follow_up
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
