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
        Schema::table('generations', function (Blueprint $table) {
            $table->addColumn('integer', 'post_id')->after('generation')->nullable();
            $table->addColumn('integer', 'page_id')->after('post_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('generations', 'post_id');
        Schema::dropColumns('generations', 'page_id');
    }
};
