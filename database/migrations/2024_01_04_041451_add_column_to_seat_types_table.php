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
        Schema::table('seat_types', function (Blueprint $table) {
            $table->integer('registeredMaleVotes')->after('seatType');
            $table->integer('registeredFemaleVotes')->after('registeredMaleVotes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seat_types', function (Blueprint $table) {
            //
        });
    }
};
