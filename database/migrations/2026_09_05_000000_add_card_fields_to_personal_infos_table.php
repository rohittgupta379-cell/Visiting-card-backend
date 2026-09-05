<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_infos', function (Blueprint $table) {
            $table->string('title')->nullable()->after('user_id');
            $table->text('profile_photo')->nullable()->after('tagline');
            $table->text('company_logo')->nullable()->after('profile_photo');
            $table->json('services')->nullable()->after('company_logo');
            $table->json('social_links')->nullable()->after('services');
            $table->string('qr_placement')->nullable()->after('social_links');
            $table->text('qr_url')->nullable()->after('qr_placement');
            $table->json('styling')->nullable()->after('qr_url');
        });
    }

    public function down(): void
    {
        Schema::table('personal_infos', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'profile_photo',
                'company_logo',
                'services',
                'social_links',
                'qr_placement',
                'qr_url',
                'styling',
            ]);
        });
    }
};