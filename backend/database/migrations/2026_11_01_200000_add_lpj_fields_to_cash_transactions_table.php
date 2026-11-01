<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('cash_transactions', 'pelaksana')) {
                $table->string('pelaksana')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('cash_transactions', 'qty')) {
                $table->decimal('qty', 10, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('cash_transactions', 'unit')) {
                $table->string('unit', 50)->nullable()->after('qty');
            }
            if (!Schema::hasColumn('cash_transactions', 'notes')) {
                $table->text('notes')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropColumn(['pelaksana', 'qty', 'unit', 'notes']);
        });
    }
};
