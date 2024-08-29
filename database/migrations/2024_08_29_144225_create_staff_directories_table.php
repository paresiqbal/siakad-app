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
        Schema::create('staff_directories', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("position", 100);
            $table->string("department", 100);
            $table->string("email", 100)->unique();
            $table->string("phone", 20);
            $table->string("image", 255)->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_directories');
    }
};
