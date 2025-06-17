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
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->text('address');
            $table->string('city');
            $table->string('email');
            $table->string('phone_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commands');
    }
};
