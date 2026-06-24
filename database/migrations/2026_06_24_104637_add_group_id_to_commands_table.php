<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commands', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('id')->index();
        });

        // Each pre-existing row predates order grouping, so treat it as its
        // own single-item order rather than leaving group_id empty.
        DB::table('commands')->whereNull('group_id')->orderBy('id')->pluck('id')->each(function ($id) {
            DB::table('commands')->where('id', $id)->update(['group_id' => (string) Str::uuid()]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commands', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
};
