<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('found_items', function (Blueprint $table) {
            $table->string('found_location')->nullable()->after('longitude');
        });
    }

    public function down()
    {
        Schema::table('found_items', function (Blueprint $table) {
            $table->dropColumn('found_location');
        });
    }
};