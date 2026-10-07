<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            // 1. Снимаем FK, который держит старый уникальный индекс
            $table->dropForeign(['venue_id']);

            // 2. Удаляем старый уникальный индекс
            $table->dropUnique('seats_venue_id_row_number_seat_number_unique');
        });

        Schema::table('seats', function (Blueprint $table) {
            // 3. Создаём новый уникальный индекс по сектору
            $table->unique(
                ['sector_id', 'row_number', 'seat_number'],
                'seats_sector_id_row_number_seat_number_unique'
            );

            // 4. Возвращаем FK на venue_id
            $table->foreign('venue_id')
                ->references('id')
                ->on('venues')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropUnique('seats_sector_id_row_number_seat_number_unique');
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->unique(
                ['venue_id', 'row_number', 'seat_number'],
                'seats_venue_id_row_number_seat_number_unique'
            );

            $table->foreign('venue_id')
                ->references('id')
                ->on('venues')
                ->cascadeOnDelete();
        });
    }
};