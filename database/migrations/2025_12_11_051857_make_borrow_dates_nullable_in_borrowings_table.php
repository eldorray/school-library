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
        // For SQLite, we need to recreate with nullable columns
        // Add new nullable columns
        Schema::table('borrowings', function (Blueprint $table) {
            $table->date('borrow_date_new')->nullable()->after('issued_by');
            $table->date('due_date_new')->nullable()->after('borrow_date_new');
        });

        // Copy data
        DB::statement('UPDATE borrowings SET borrow_date_new = borrow_date, due_date_new = due_date');

        // Drop old columns
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['borrow_date', 'due_date']);
        });

        // Rename new columns
        Schema::table('borrowings', function (Blueprint $table) {
            $table->renameColumn('borrow_date_new', 'borrow_date');
            $table->renameColumn('due_date_new', 'due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible safely
    }
};
