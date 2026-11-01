<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_categories', 'type')) {
                $table->string('type')->default('INVENTORY')->after('name');
            }
            if (!Schema::hasColumn('inventory_categories', 'description')) {
                $table->text('description')->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_categories', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_categories', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('inventory_categories', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};

