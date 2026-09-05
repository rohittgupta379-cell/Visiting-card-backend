<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('personal_infos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('full_name');
        $table->string('job_designation');
        $table->string('company_name');
        $table->string('phone');
        $table->string('whatsapp_number')->nullable();
        $table->string('email');
        $table->string('website_url')->nullable();
        $table->text('business_address')->nullable();
        $table->text('tagline')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_infos');
    }
};
