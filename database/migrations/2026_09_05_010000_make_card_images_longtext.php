<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE personal_infos MODIFY profile_photo LONGTEXT NULL, MODIFY company_logo LONGTEXT NULL, MODIFY qr_url LONGTEXT NULL'
        );
    }

    public function down(): void
    {
        DB::statement(
            'ALTER TABLE personal_infos MODIFY profile_photo TEXT NULL, MODIFY company_logo TEXT NULL, MODIFY qr_url TEXT NULL'
        );
    }
};