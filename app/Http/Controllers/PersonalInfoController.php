<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;

class PersonalInfoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'job_designation' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'whatsapp_number' => 'nullable|string|max:15',
            'email' => 'required|email|max:255',
            'website_url' => 'nullable|url|max:255',
            'business_address' => 'nullable|string',
            'tagline' => 'nullable|string',
        ]);

        $personalInfo = PersonalInfo::create([
            'full_name' => $request->full_name,
            'job_designation' => $request->job_designation,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'email' => $request->email,
            'website_url' => $request->website_url,
            'business_address' => $request->business_address,
            'tagline' => $request->tagline,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Personal information saved successfully',
            'data' => $personalInfo,
        ], 201);
    }
}