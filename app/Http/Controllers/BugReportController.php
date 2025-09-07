<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BugReport;
use Illuminate\Support\Facades\Auth;

class BugReportController extends Controller
{
    public function showForm()
    {
        return view('bug_report.form');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'screenshot' => 'nullable|image|max:2048',
        ]);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('bug_screenshots', 'public');
        }

        BugReport::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'screenshot' => $screenshotPath,
        ]);

        return redirect()->back()->with('success', 'Bug report submitted successfully.');
    }
}
