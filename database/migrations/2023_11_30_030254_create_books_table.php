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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable(false);
            $table->text('trailer')->nullable(false);
            $table->year('publication_year')->nullable(false);
            $table->unsignedBigInteger("quantity")->default(0);
            $table->string('author', 100)->nullable(false);
            $table->string('publisher', 100)->nullable(false);
            $table->char('shell_code', 4)->nullable(false);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);

            $table->foreign('category_id')->on('categories')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
