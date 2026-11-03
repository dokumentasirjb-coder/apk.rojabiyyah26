<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('duty_name')->nullable()->after('role_in_post');
            $table->string('category')->nullable()->after('duty_name');
            $table->string('execution_time')->nullable()->after('category');
            $table->string('specific_location')->nullable()->after('execution_time');
            $table->text('description')->nullable()->after('specific_location');
            $table->timestamp('notif_assigned_at')->nullable()->after('end_at');
            $table->timestamp('notif_10m_at')->nullable()->after('notif_assigned_at');
            $table->timestamp('notif_start_at')->nullable()->after('notif_10m_at');
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn([
                'duty_name',
                'category',
                'execution_time',
                'specific_location',
                'description',
                'notif_assigned_at',
                'notif_10m_at',
                'notif_start_at',
            ]);
        });
    }
};
