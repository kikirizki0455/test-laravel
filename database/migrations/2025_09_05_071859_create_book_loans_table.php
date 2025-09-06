<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamp('borrowed_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            // index untuk query cepat
            $table->index(['user_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};
