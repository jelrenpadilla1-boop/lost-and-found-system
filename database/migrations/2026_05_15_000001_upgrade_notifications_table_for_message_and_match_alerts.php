<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->default('info')->after('user_id')->index();
            }

            if (!Schema::hasColumn('notifications', 'body')) {
                $table->text('body')->nullable()->after('title');
            }

            if (!Schema::hasColumn('notifications', 'url')) {
                $table->string('url')->nullable()->after('body');
            }

            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable()->after('url');
            }
        });

        if (Schema::hasColumn('notifications', 'message') && Schema::hasColumn('notifications', 'body')) {
            DB::table('notifications')
                ->whereNull('body')
                ->update(['body' => DB::raw('message')]);
        }
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'data')) {
                $table->dropColumn('data');
            }

            if (Schema::hasColumn('notifications', 'url')) {
                $table->dropColumn('url');
            }

            if (Schema::hasColumn('notifications', 'body')) {
                $table->dropColumn('body');
            }

            if (Schema::hasColumn('notifications', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
