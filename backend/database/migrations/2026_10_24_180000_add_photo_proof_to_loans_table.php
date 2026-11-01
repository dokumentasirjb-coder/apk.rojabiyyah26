<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'photo_proof')) {
                $table->longText('photo_proof')->nullable()->after('returned_at');
            }
            if (!Schema::hasColumn('loans', 'return_notes')) {
                $table->text('return_notes')->nullable()->after('photo_proof');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'return_notes')) {
                $table->dropColumn('return_notes');
            }
            if (Schema::hasColumn('loans', 'photo_proof')) {
                $table->dropColumn('photo_proof');
            }
        });
    }
};

