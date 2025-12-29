<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('label')->get()->groupBy('group');
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
        ]);

        foreach ($request->input('settings', []) as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // Handle boolean checkboxes (unchecked = null)
                if ($setting->type === 'boolean') {
                    $value = $value ?? 'false';
                }
                
                $setting->update(['value' => $value]);
            }
        }

        // Clear cache
        Setting::clearCache();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', '¡Configuración actualizada correctamente!');
    }
}
