<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('notifications', 'message')) {
            DB::statement('ALTER TABLE notifications MODIFY message TEXT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('notifications', 'message')) {
            DB::statement("UPDATE notifications SET message = '' WHERE message IS NULL");
            DB::statement('ALTER TABLE notifications MODIFY message TEXT NOT NULL');
        }
    }
};
