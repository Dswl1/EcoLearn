<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('public_access');
            $table->timestamp('submitted_at')->nullable()->after('rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'submitted_at']);
        });
    }
};
