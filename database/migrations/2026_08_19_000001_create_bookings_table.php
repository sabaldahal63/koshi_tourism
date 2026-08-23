<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // hotel, guide, destination
            $table->string('item_id')->nullable();
            $table->string('title');
            $table->string('image')->nullable();
            $table->decimal('price_per_unit', 12, 2)->default(0);
            $table->string('name');
            $table->string('email');
            $table->string('date');
            $table->integer('guests')->default(1);
            $table->integer('nights')->default(1);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('status')->default('confirmed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
