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
        Schema::table('fixtures', function (Blueprint $table) {
            $table->addColumn('integer', 'home_team_id')->after('home_logo')->nullable();
            $table->addColumn('integer', 'away_team_id')->after('away_logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('fixtures', 'home_team_id');
        Schema::dropColumns('fixtures', 'away_team_id');
    }
};
