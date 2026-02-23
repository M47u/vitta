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

    public function bankSettings()
    {
        $bankSettings = [
            'bank_name' => Setting::get('bank_name', ''),
            'bank_account_type' => Setting::get('bank_account_type', ''),
            'bank_holder' => Setting::get('bank_holder', ''),
            'bank_cuit' => Setting::get('bank_cuit', ''),
            'bank_cbu' => Setting::get('bank_cbu', ''),
            'bank_alias' => Setting::get('bank_alias', ''),
        ];

        return view('admin.settings.bank', compact('bankSettings'));
    }

    public function updateBankSettings(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_account_type' => 'required|string|max:255',
            'bank_holder' => 'required|string|max:255',
            'bank_cuit' => 'required|string|max:20',
            'bank_cbu' => 'required|string|size:22',
            'bank_alias' => 'required|string|max:50',
        ], [
            'bank_name.required' => 'El nombre del banco es obligatorio',
            'bank_account_type.required' => 'El tipo de cuenta es obligatorio',
            'bank_holder.required' => 'El titular de la cuenta es obligatorio',
            'bank_cuit.required' => 'El CUIT/CUIL es obligatorio',
            'bank_cbu.required' => 'El CBU es obligatorio',
            'bank_cbu.size' => 'El CBU debe tener exactamente 22 dígitos',
            'bank_alias.required' => 'El alias es obligatorio',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, null, 'text', 'payment');
        }

        return redirect()
            ->route('admin.settings.bank')
            ->with('success', '¡Datos bancarios actualizados correctamente!');
    }
}
