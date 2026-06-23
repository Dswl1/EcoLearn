<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->index('sdg_category');
            $table->index('difficulty');
            $table->index('status');
            $table->index('published_at');
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE contents ADD FULLTEXT ft_search(title, description, tags)');
        }
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropIndex(['sdg_category']);
            $table->dropIndex(['difficulty']);
            $table->dropIndex(['status']);
            $table->dropIndex(['published_at']);
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE contents DROP INDEX ft_search');
        }
    }
};
