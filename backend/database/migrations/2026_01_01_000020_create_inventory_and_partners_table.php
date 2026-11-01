<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact')->nullable();
            $table->date('return_due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('source', ['internal', 'mitra'])->default('internal');
            $table->foreignId('partner_id')->nullable()->constrained('partners')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('inventory_categories')->onDelete('set null');
            $table->integer('qty_total')->default(0);
            $table->integer('qty_available')->default(0);
            $table->integer('qty_reserved')->default(0);
            $table->integer('qty_damaged')->default(0);
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->string('photo_path')->nullable();
            $table->string('location')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('custom_field_defs', function (Blueprint $table) {
            $table->id();
            $table->string('entity'); // e.g. items, members
            $table->string('label');
            $table->enum('type', ['text', 'number', 'select'])->default('text');
            $table->json('options')->nullable();
            $table->timestamps();
        });

        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('def_id')->constrained('custom_field_defs')->onDelete('cascade');
            $table->unsignedBigInteger('entity_id');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index(['entity_id', 'def_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_field_values');
        Schema::dropIfExists('custom_field_defs');
        Schema::dropIfExists('items');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('inventory_categories');
    }
};

