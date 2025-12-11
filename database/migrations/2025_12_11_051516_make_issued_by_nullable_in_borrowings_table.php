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
        // SQLite doesn't support modifying columns directly
        // We need to recreate the table structure
        Schema::table('borrowings', function (Blueprint $table) {
            $table->unsignedBigInteger('issued_by_new')->nullable()->after('book_id');
        });

        // Copy data
        DB::statement('UPDATE borrowings SET issued_by_new = issued_by');

        // Drop old foreign key and column, rename new column
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeign(['issued_by']);
            $table->dropColumn('issued_by');
        });

        Schema::table('borrowings', function (Blueprint $table) {
            $table->renameColumn('issued_by_new', 'issued_by');
        });

        // Add foreign key back
        Schema::table('borrowings', function (Blueprint $table) {
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('cascade');
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
