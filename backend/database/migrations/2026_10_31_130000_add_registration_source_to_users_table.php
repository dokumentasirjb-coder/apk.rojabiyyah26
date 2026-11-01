<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'registration_source')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('registration_source', 30)->default('google_form')->after('role');
            });

            // Update existing users who have email to 'account'
            DB::table('users')->whereNotNull('email')->update(['registration_source' => 'account']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'registration_source')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('registration_source');
            });
        }
    }
};

