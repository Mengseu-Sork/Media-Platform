<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publishing_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_target_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('action');

            $table->unsignedSmallInteger('http_status')->nullable();

            $table->text('response')->nullable();

            $table->text('error')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publishing_logs');
    }
};
