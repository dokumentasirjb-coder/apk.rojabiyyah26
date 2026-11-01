<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            if (!Schema::hasColumn('regions', 'code')) {
                $table->string('code', 50)->nullable()->after('name');
            }
        });

        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'phone')) {
                $table->string('phone', 50)->nullable()->after('contact');
            }
            if (!Schema::hasColumn('partners', 'description')) {
                $table->text('description')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            if (Schema::hasColumn('regions', 'code')) {
                $table->dropColumn('code');
            }
        });

        Schema::table('partners', function (Blueprint $table) {
            if (Schema::hasColumn('partners', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('partners', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

