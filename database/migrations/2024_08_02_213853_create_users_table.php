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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document')->unique(); 
            $table->string('name', 400); 
            $table->unsignedBigInteger('phone')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('groupId'); 
            $table->unsignedBigInteger('rol'); 
            $table->integer('status'); 
            $table->foreign('groupId')->references('id')->on('groups'); //Relación 1 a muchos con los grupos
            $table->foreign('rol')->references('id')->on('roles'); //Relación de 1 a 1 con los roles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
