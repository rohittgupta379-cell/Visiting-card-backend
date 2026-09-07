<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_infos', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false)->after('id');
            $table->boolean('is_default')->default(false)->after('is_delete');
        });
    }

    public function down(): void
    {
        Schema::table('personal_infos', function (Blueprint $table) {
            $table->dropColumn([
                'is_delete',
                'is_default',
            ]);
        });
    }
};