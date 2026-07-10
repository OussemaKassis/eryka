<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commands', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('group_id');
        });

        // Backfill so existing rows keep their current latest-first order
        // (by created_at) instead of all colliding on the column default.
        DB::table('commands')
            ->orderByDesc('created_at')
            ->pluck('id')
            ->each(function ($id, $position) {
                DB::table('commands')->where('id', $id)->update(['sort_order' => $position]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commands', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
