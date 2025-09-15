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
        Schema::create('order_meeting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_room_id')->constrained('meeting_room', 'id');
            $table->foreignId('unit_id')->constrained('unit', 'id');
            $table->date('date');
            $table->time('start');
            $table->time('end');
            $table->integer('quantity_member');
            $table->integer('price');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->string('consumption');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_meeting');
    }
};
