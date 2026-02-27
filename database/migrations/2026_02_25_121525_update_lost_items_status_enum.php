<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update the ENUM to include all needed statuses
        DB::statement("ALTER TABLE lost_items MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'found', 'returned') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert back to original ENUM
        DB::statement("ALTER TABLE lost_items MODIFY COLUMN status ENUM('pending', 'found', 'returned') DEFAULT 'pending'");
    }
};