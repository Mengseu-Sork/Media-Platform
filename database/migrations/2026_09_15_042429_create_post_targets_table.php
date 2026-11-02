<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_targets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('social_account_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('status', [
                'pending',
                'publishing',
                'published',
                'failed',
            ])->default('pending');

            $table->text('error_message')->nullable();

            $table->string('external_post_id')->nullable();

            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->unique([
                'post_id',
                'social_account_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_targets');
    }
};