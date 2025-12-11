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
        // For SQLite, we need to recreate the column with new enum values
        // First, add new columns
        Schema::table('borrowings', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('notes');
        });

        // Since SQLite doesn't support ALTER COLUMN for enum, 
        // we'll use a workaround with a new column
        Schema::table('borrowings', function (Blueprint $table) {
            $table->string('status_new')->default('pending')->after('status');
        });

        // Copy data
        DB::statement("UPDATE borrowings SET status_new = status");

        // Drop old column and rename
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('borrowings', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });

        // Make issued_by and borrow_date nullable for pending requests
        // (These will be set when approved)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
