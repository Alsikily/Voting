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
        Schema::create('polls', function (Blueprint $table) {
            $table -> id();
            $table -> unsignedBigInteger('choose_id');
            $table -> foreign('choose_id')
                    -> references('id')
                    -> on('vote_chooses');
            $table -> unsignedBigInteger('user_id');
            $table -> foreign('user_id')
                    -> references('id')
                    -> on('users');
        });
    }

    public function down(): void {
        Schema::dropIfExists('polls');
    }

};
