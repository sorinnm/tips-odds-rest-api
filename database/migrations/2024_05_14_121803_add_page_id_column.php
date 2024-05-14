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
            $table->addColumn('integer', 'page_id')->after('category_path')->nullable();
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->addColumn('integer', 'page_id')->after('category_path')->nullable();
        });

        Schema::table('leagues', function (Blueprint $table) {
            $table->addColumn('integer', 'page_id')->after('category_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('sports', 'page_id');
        Schema::dropColumns('countries', 'page_id');
        Schema::dropColumns('leagues', 'page_id');
    }
};
