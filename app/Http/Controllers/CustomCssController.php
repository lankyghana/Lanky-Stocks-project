<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class CustomCssController extends Controller
{
    public function show()
    {
        $generalSetting = GeneralSetting::first();
        return view('admin.custom_css', compact('generalSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'custom_css' => 'nullable|string',
        ]);
        $generalSetting = GeneralSetting::first();
        $generalSetting->custom_css = $request->custom_css;
        $generalSetting->save();
        return redirect()->back()->with('success', 'Custom CSS updated successfully.');
    }
}
