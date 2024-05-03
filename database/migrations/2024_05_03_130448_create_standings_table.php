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
        Schema::create('standings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('league_id')->index('FIXTURE_ID');
            $table->integer('season_id')->index('SEASON_ID');
            $table->string('round')->index('ROUND');
            $table->longText('standings')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->index(['league_id', 'season_id', 'round'], 'FIXTURE_ID_SEASON_ID_ROUND');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
