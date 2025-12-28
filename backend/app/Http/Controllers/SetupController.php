<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SetupController extends Controller
{
    public function setup(Request $request)
    {
        // Check if already setup
        if (User::exists()) {
            return response()->json(['message' => 'Setup already completed'], 400);
        }

        $request->validate([
            'app_name' => 'required',
            'pterodactyl_url' => 'required|url',
            'pterodactyl_key' => 'required',
            'admin_username' => 'required',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:8',
        ]);

        // Save settings to environment or DB
        // For MVP, simplistic .env writing or DB storage
        Setting::create(['key' => 'app_name', 'value' => $request->app_name]);
        
        // Write to .env generally requires a library or parsing, simplified here:
        $this->updateEnv([
            'APP_NAME' => '"' . $request->app_name . '"',
            'PT_URL' => $request->pterodactyl_url,
            'PT_API_KEY' => $request->pterodactyl_key,
        ]);

        // Create Admin User
        $user = User::create([
            'username' => $request->admin_username,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin',
            'pterodactyl_user_id' => 1, // Assume root admin linked to ID 1 or fetch logic
        ]);

        return response()->json(['message' => 'Setup completed']);
    }

    protected function updateEnv(array $data)
    {
        $path = base_path('.env');
        if (File::exists($path)) {
            $content = File::get($path);
            foreach ($data as $key => $value) {
                if (str_contains($content, "$key=")) {
                   $content = preg_replace("/^$key=.*/m", "$key=$value", $content);
                } else {
                   $content .= "\n$key=$value";
                }
            }
            File::put($path, $content);
        }
    }
}
