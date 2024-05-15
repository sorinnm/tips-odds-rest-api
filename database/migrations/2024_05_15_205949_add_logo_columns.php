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
            $table->addColumn('text', 'home_logo')->after('status')->nullable();
            $table->addColumn('text', 'away_logo')->after('home_logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('fixtures', 'home_logo');
        Schema::dropColumns('fixtures', 'away_logo');
    }
};
