<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
   public function updateDashboardStyle(Request $request)
{
    $request->validate([
        'dashboard_style' => 'required|integer|min:1|max:9',
    ]);

    $user = auth()->user();
    $user->dashboard_style = $request->dashboard_style;
    $user->save();

    return back();
}
}
