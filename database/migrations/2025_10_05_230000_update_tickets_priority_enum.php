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
        // Update the enum to include 'urgent'. Use a direct statement because
        // modifying ENUM columns requires raw SQL in many MySQL versions.
        DB::statement("ALTER TABLE `tickets` MODIFY `priority` ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the previous enum that contained 'critical'.
        DB::statement("ALTER TABLE `tickets` MODIFY `priority` ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium'");
    }
};
