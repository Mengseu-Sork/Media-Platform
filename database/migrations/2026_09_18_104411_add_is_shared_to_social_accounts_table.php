<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->boolean('is_shared')
                ->default(false)
                ->after('is_active');

            $table->index([
                'platform',
                'is_active',
                'is_shared',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->dropIndex([
                'social_accounts_platform_is_active_is_shared_index',
            ]);

            $table->dropColumn('is_shared');
        });
    }
};
