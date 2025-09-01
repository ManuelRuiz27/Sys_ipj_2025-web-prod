<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'uuid')) {
                $table->uuid('uuid')->after('id')->unique()->nullable();
            }
        });

        // Backfill existing users
        DB::table('users')->whereNull('uuid')->update(['uuid' => DB::raw('UUID()')]);

        // Ensure not null going forward
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'uuid')) {
                $table->dropUnique(['uuid']);
                $table->dropColumn('uuid');
            }
        });
    }
};

