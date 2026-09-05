<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;

class PersonalInfoController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => PersonalInfo::where('user_id', $request->user()->id)
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedCard($request);
        $data['user_id'] = $request->user()->id;
        $personalInfo = PersonalInfo::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Personal information saved successfully',
            'data' => $personalInfo,
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $data = $this->validatedCard($request);

        $personalInfo = PersonalInfo::where('user_id', $request->user()->id)
            ->findOrFail($id);
        $personalInfo->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Personal information updated successfully',
            'data' => $personalInfo,
        ]);
    }

    private function validatedCard(Request $request): array
    {
        return $request->validate([
            'title' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'job_designation' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'whatsapp_number' => 'nullable|string|max:30',
            'email' => 'required|email|max:255',
            'website_url' => 'nullable|url|max:255',
            'business_address' => 'nullable|string',
            'tagline' => 'nullable|string',
            'profile_photo' => 'nullable|string',
            'company_logo' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'string|max:255',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|string|max:255',
            'qr_placement' => 'nullable|in:front,back,both,none',
            'qr_url' => 'nullable|url|max:2048',
            'styling' => 'nullable|array',
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $personalInfo = PersonalInfo::where('user_id', $request->user()->id)
            ->findOrFail($id);
        $personalInfo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Personal information deleted successfully',
        ]);
    }
}