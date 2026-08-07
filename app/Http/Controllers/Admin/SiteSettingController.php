<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $groups = SiteSetting::orderBy('group')->get()->groupBy('group');
        return view('admin.settings.index', compact('groups'));
    }

    public function update(Request $request)
    {
        $values = $request->input('settings', []);
        foreach ($values as $key => $value) {
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }

        // image uploads (settings[key] as file via settings_files[key])
        foreach ($request->file('settings_files', []) as $key => $file) {
            $path = $file->store('uploads', 'public');
            SiteSetting::where('key', $key)->update(['value' => $path]);
        }

        \Illuminate\Support\Facades\Cache::forget('site_settings');

        return back()->with('success', 'Website content updated.');
    }
}
