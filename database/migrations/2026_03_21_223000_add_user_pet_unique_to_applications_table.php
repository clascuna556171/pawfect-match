<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keep the earliest row per (user_id, pet_id) pair and remove the rest.
        $duplicates = DB::table('applications')
            ->select('user_id', 'pet_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'pet_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('applications')
                ->where('user_id', $duplicate->user_id)
                ->where('pet_id', $duplicate->pet_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('applications', function (Blueprint $table) {
            $table->unique(['user_id', 'pet_id'], 'applications_user_pet_unique');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique('applications_user_pet_unique');
        });
    }
};
