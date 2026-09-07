<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;

class PersonalInfoController extends Controller
{
    public function index(Request $request)
    {
        $data = PersonalInfo::where('user_id', $request->user()->id)->where('is_delete', false)->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    
    public function getDefault(Request $request)
    {
        $query = PersonalInfo::where('user_id', $request->user()->id)
            ->where('is_delete', false);
        $data = (clone $query)->where('is_default', true)->first();

        if (!$data) {
            $data = $query->latest()->first();
            if ($data) {
                $data->update(['is_default' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedCard($request);
        $data = $this->storeImages($request, $data);
        $data['user_id'] = $request->user()->id;
        $data['is_default'] = !PersonalInfo::where('user_id', $data['user_id'])
            ->where('is_delete', false)
            ->where('is_default', true)
            ->exists();
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
        $data = $this->storeImages($request, $data);

        $personalInfo = PersonalInfo::where('user_id', $request->user()->id)->findOrFail($id);
        $personalInfo->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Personal information updated successfully',
            'data' => $personalInfo,
        ]);
    }

    private function validatedCard(Request $request): array
    {
        foreach (['services', 'social_links', 'styling'] as $field) {
            if (is_string($request->input($field))) {
                $request->merge([
                    $field => json_decode($request->input($field), true) ?: [],
                ]);
            }
        }

        $profilePhotoRule = $request->hasFile('profile_photo')
            ? 'nullable|image|max:5120'
            : 'nullable|url|max:2048';
        $companyLogoRule = $request->hasFile('company_logo')
            ? 'nullable|image|max:5120'
            : 'nullable|url|max:2048';

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
            'profile_photo' => $profilePhotoRule,
            'company_logo' => $companyLogoRule,
            'services' => 'nullable|array',
            'services.*' => 'string|max:255',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|string|max:255',
            'qr_placement' => 'nullable|in:front,back,both,none',
            'qr_url' => 'nullable|url|max:2048',
            'styling' => 'nullable|array',
        ]);
    }

    private function storeImages(Request $request, array $data): array
    {
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('cards', 'public');
            $data['profile_photo'] = url("storage/{$path}");
        }

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('cards', 'public');
            $data['company_logo'] = url("storage/{$path}");
        }

        return $data;
    }

    public function destroy(Request $request, int $id)
    {
        $personalInfo = PersonalInfo::where('user_id', $request->user()->id)->findOrFail($id);

        $personalInfo->update([
            'is_delete' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Personal information deleted successfully',
        ]);
    }
    
    public function default(Request $request, int $id)
    {
        $personalInfo = PersonalInfo::where('user_id', $request->user()->id)
            ->where('is_delete', false)
            ->findOrFail($id);

        PersonalInfo::where('user_id', $request->user()->id)
            ->where('is_delete', false)
            ->update(['is_default' => false]);

        $personalInfo->update([
            'is_default' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Personal information set as default successfully',
        ]); 
    }
}
