<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_no')->unique();
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->text('purpose');
            $table->enum('status', [
                'diajukan',
                'disetujui',
                'diserahkan',
                'dikembalikan',
                'ditolak',
                'dibatalkan',
                'terlambat',
                'dikembalikan_sebagian',
                'hilang_rusak'
            ])->default('diajukan');
            $table->dateTime('requested_at');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('handed_over_at')->nullable();
            $table->dateTime('due_at');
            $table->dateTime('returned_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('verification_code', 64)->unique();
            $table->timestamps();
        });

        Schema::create('loan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('qty_requested');
            $table->integer('qty_approved')->default(0);
            $table->integer('qty_returned')->default(0);
            $table->string('condition_out')->nullable();
            $table->string('condition_in')->nullable();
            $table->string('photo_out')->nullable();
            $table->string('photo_in')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_items');
        Schema::dropIfExists('loans');
    }
};

