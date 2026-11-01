<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role_in_post');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('field_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['normal', 'perlu_perhatian', 'rusak_kendala'])->default('normal');
            $table->enum('followup_status', ['open', 'in_progress', 'resolved'])->default('open');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('report_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('field_reports')->onDelete('cascade');
            $table->string('path');
            $table->enum('type', ['photo', 'video'])->default('photo');
            $table->timestamps();
        });

        Schema::create('report_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('field_reports')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['open', 'in_progress', 'resolved'])->default('in_progress');
            $table->text('solution');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_followups');
        Schema::dropIfExists('report_media');
        Schema::dropIfExists('field_reports');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('regions');
    }
};

