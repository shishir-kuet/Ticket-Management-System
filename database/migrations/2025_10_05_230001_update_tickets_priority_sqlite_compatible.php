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
        if (DB::connection()->getDriverName() === 'sqlite') {
            // For SQLite, use CHECK constraint instead of ENUM
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('priority')->default('medium')->change();
            });
            DB::statement("ALTER TABLE tickets ADD CHECK (priority IN ('low','medium','high','urgent'))");
        } else {
            // For MySQL, use ENUM
            DB::statement("ALTER TABLE `tickets` MODIFY `priority` ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('priority')->default('medium')->change();
            });
            DB::statement("ALTER TABLE tickets ADD CHECK (priority IN ('low','medium','high','critical'))");
        } else {
            DB::statement("ALTER TABLE `tickets` MODIFY `priority` ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium'");
        }
    }
};