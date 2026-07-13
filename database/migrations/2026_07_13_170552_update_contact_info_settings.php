<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        DB::table('settings')->updateOrInsert(
            ['key' => 'phone'],
            ['value' => '01985902350', 'updated_at' => $now, 'created_at' => $now],
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'whatsapp'],
            ['value' => '01985902350', 'updated_at' => $now, 'created_at' => $now],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'whatsapp')->delete();

        DB::table('settings')->where('key', 'phone')->update([
            'value' => '+8801700000000',
            'updated_at' => now(),
        ]);
    }
};
