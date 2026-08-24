<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE categories ALTER COLUMN icon TYPE TEXT');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE categories MODIFY icon TEXT');
        } else {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('icon')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
