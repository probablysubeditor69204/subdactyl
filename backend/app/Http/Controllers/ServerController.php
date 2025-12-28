<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\AllowedNode;
use App\Models\AllowedEgg;
use App\Services\PterodactylService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->servers;
    }

    public function store(Request $request, PterodactylService $ptero)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'node_id' => 'required|exists:allowed_nodes,id',
            'egg_id' => 'required|exists:allowed_eggs,id',
        ]);

        $user = $request->user();

        // Check limits (simplified for now)
        if ($user->servers()->count() >= 3) {
            return response()->json(['error' => 'Server limit reached'], 403);
        }

        $node = AllowedNode::findOrFail($request->node_id);
        $egg = AllowedEgg::findOrFail($request->egg_id);

        try {
            $pteroServer = $ptero->createServer([
                'name' => $request->name,
                'user_id' => $user->pterodactyl_user_id,
                'egg_id' => $egg->pterodactyl_egg_id,
                'docker_image' => 'ghcr.io/pterodactyl/yolks:java_17', // Example, needs to be dynamic based on egg
                'startup' => 'java -Xms128M -Xmx{{SERVER_MEMORY}}M -jar server.jar',
                'environment' => [
                    'SERVER_JARFILE' => 'server.jar',
                    'VANILLA_VERSION' => 'latest',
                ],
                'memory' => 1024, // Free tier limit
                'disk' => 5000,    // Free tier limit
                'cpu' => 100,      // Free tier limit
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $server = Server::create([
            'user_id' => $user->id,
            'pterodactyl_server_id' => $pteroServer['id'],
            'name' => $request->name,
            'identifier' => $pteroServer['identifier'],
            'node_id' => $node->id,
            'egg_id' => $egg->id,
            'status' => 'installing',
        ]);

        return response()->json($server, 201);
    }
}
