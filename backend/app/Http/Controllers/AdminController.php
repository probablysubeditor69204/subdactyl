<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\AllowedNode;
use App\Models\AllowedEgg;
use App\Services\PterodactylService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function syncResources(PterodactylService $ptero)
    {
        try {
            $nodes = $ptero->listNodes();
            foreach ($nodes as $nodeData) {
                AllowedNode::updateOrCreate(
                    ['pterodactyl_node_id' => $nodeData['attributes']['id']],
                    ['name' => $nodeData['attributes']['name']]
                );
            }

            // Sync Eggs (only nest 1 for now)
            $eggs = $ptero->listEggs(1); 
            foreach ($eggs as $eggData) {
                AllowedEgg::updateOrCreate(
                    ['pterodactyl_egg_id' => $eggData['attributes']['id']],
                    ['name' => $eggData['attributes']['name']]
                );
            }

            return response()->json(['message' => 'Resources synced successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'dashboard_name' => 'nullable|string',
            'logo_url' => 'nullable|url',
        ]);

        foreach ($data as $key => $value) {
            if ($value) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        return response()->json(['message' => 'Settings updated']);
    }
}
