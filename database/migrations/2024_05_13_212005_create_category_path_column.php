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
        Schema::table('sports', function (Blueprint $table) {
            $table->addColumn('tinytext', 'category_path')->after('category_id')->nullable();
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->addColumn('tinytext', 'category_path')->after('category_id')->nullable();
        });

        Schema::table('leagues', function (Blueprint $table) {
            $table->addColumn('tinytext', 'category_path')->after('category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('sports', 'category_path');
        Schema::dropColumns('countries', 'category_path');
        Schema::dropColumns('leagues', 'category_path');
    }
};
