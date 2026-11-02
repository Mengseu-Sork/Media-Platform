<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the slideshow path to the existing posts table.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('posts', 'youtube_slideshow_path')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('youtube_slideshow_path')->nullable();
            });
        }
    }

    /**
     * Remove the slideshow path if this migration is rolled back.
     */
    public function down(): void
    {
        if (Schema::hasColumn('posts', 'youtube_slideshow_path')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('youtube_slideshow_path');
            });
        }
    }
};