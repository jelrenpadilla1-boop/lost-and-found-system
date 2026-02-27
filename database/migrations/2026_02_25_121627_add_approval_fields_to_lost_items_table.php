<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lost_items', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->after('rejected_at');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
        });
    }

    public function down(): void
    {
        Schema::table('lost_items', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'rejection_reason'
            ]);
        });
    }
};