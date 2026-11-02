<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->string('title_Kh')->nullable();

            $table->longText('content')->nullable();
            $table->longText('content_Kh')->nullable();

            $table->enum('status', [
                'draft',
                'scheduled',
                'publishing',
                'published',
                'failed',
            ])->default('draft');

            $table->timestamp('scheduled_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
