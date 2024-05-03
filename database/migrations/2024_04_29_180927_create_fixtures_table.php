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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fixture_id')->index('FIXTURE_ID');
            $table->longText('fixtures')->nullable();
            $table->longText('home_team_squad')->nullable();
            $table->longText('away_team_squad')->nullable();
            $table->longText('injuries')->nullable();
            $table->longText('predictions')->nullable();
            $table->longText('head_to_head')->nullable();
            $table->longText('bets')->nullable();
            $table->enum('status', ['pending', 'running', 'retry', 'error', 'complete'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->index('fixture_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
