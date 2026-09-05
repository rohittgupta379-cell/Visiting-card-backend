<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'job_designation',
        'company_name',
        'phone',
        'whatsapp_number',
        'email',
        'website_url',
        'business_address',
        'tagline',
    ];
}