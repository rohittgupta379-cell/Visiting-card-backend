<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'full_name',
        'job_designation',
        'company_name',
        'phone',
        'whatsapp_number',
        'email',
        'website_url',
        'business_address',
        'tagline',
        'profile_photo',
        'company_logo',
        'services',
        'social_links',
        'qr_placement',
        'qr_url',
        'styling',
    ];

    protected $casts = [
        'services' => 'array',
        'social_links' => 'array',
        'styling' => 'array',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}