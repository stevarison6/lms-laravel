<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){

        
        $stats = [
            'totalCourses' => Course::count(),
            'totalUsers' => User::count(),
            'totalQuizzes' => Quiz::count(),
        ];
        //dd($stats);

        return Inertia::render('AdminPage', [
            'stats' => $stats
        ]);
    }
    public function edit()
    {
        $setting = Setting::first();
        return Inertia::render('Admin/SettingsPage', [
            'setting' => $setting
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'items_per_page' => 'required|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        Setting::create($validated);

        return redirect()->route('admin.settings.edit')->with('success', 'Paramètres enregistrés avec succès.');
    }

public function update(Request $request, Setting $setting)
    {
        //dd($request);
        // Validation des entrées
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'items_per_page' => 'required|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        // Mise à jour des paramètres
        $setting->site_name = $request->site_name;
        $setting->site_description = $request->site_description;
        $setting->items_per_page = $request->items_per_page;

        // Traitement du logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logos');
            $setting->logo_path = basename($logoPath); // Sauvegarde seulement le nom du fichier
        }
        //dd($request->file('logo'));
        $setting->save();

        return redirect()->back()->with('success', 'Paramètres mis à jour avec succès.');
    }

    
}
