<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->change();
            }

            if (Schema::hasColumn('posts', 'is_active')) {
                $table->string('is_active', 10)->nullable()->default('Yes')->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            
        });
    }
};
