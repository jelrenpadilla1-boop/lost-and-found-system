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
        DB::statement("ALTER TABLE found_items MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'claimed', 'returned', 'disposed') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert back to original ENUM
        DB::statement("ALTER TABLE found_items MODIFY COLUMN status ENUM('pending', 'claimed', 'disposed') DEFAULT 'pending'");
    }
    
};