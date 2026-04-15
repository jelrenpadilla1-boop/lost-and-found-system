<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFoundAndReturnedAtToLostItemsTable extends Migration
{
    public function up()
    {
        Schema::table('lost_items', function (Blueprint $table) {
            if (!Schema::hasColumn('lost_items', 'found_at')) {
                $table->timestamp('found_at')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('lost_items', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('found_at');
            }
        });
    }

    public function down()
    {
        Schema::table('lost_items', function (Blueprint $table) {
            $table->dropColumn(['found_at', 'returned_at']);
        });
    }
}