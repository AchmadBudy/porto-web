<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralSettingsResource;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SetupWebController extends Controller
{
    public function index(GeneralSettings $settings)
    {
        return inertia('admin/setup-web/Index', [
            'generalSettings' => GeneralSettingsResource::make($settings),
        ]);
    }

    public function update(Request $request, GeneralSettings $settings)
    {
        // try {
        $validate = $request->validate([
            'name' => ['required', 'string'],
            'title' => ['required', 'string'],
            'bio' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'skills' => ['required', 'array'],
            'skills.*' => ['required', 'string'],
            'socials' => ['required', 'array'],
            'socials.*.name' => ['required', 'string'],
            'socials.*.url' => ['required', 'string'],
            'socials.*.imageUrl' => ['nullable', 'image', 'max:2048'],
        ]);

        // check if avatar is uploaded
        if ($request->hasFile('avatar')) {
            // delete old avatar if exists
            if ($settings->avatar) {
                Storage::delete($settings->avatar);
            }
            $validate['avatar'] = Storage::disk('public')->put('avatars', $validate['avatar']);
        }

        // Process socials with file uploads
        $socials = $settings->socials;
        foreach ($validate['socials'] as $key => $social) {
            // Check if there's a file upload for this social
            $fieldName = "socials.{$key}.imageUrl";
            if ($request->hasFile($fieldName)) {

                // Delete old image if exists
                if ($socials[$key]['imageUrl']) {
                    Storage::disk('public')->delete($socials[$key]['imageUrl']);
                }

                // Store the new file
                $validate['socials'][$key]['imageUrl'] = Storage::disk('public')->put(
                    'socials',
                    $request->file($fieldName)
                );
            } else {
                // If no file upload, use the existing image URL
                $validate['socials'][$key]['imageUrl'] = $socials[$key]['imageUrl'];
            }
        }

        $settings->fill($validate);
        $settings->save();

        return to_route('admin.settings.web-setup')->with('success', 'Global Setting Has been Updated');
    }
}
