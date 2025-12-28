<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class PterodactylService
{
    protected string $url;
    protected string $apiKey;

    public function __construct()
    {
        $this->url = config('services.pterodactyl.url') ?? env('PT_URL');
        $this->apiKey = config('services.pterodactyl.key') ?? env('PT_API_KEY');
    }

    protected function client()
    {
        if (!$this->url || !$this->apiKey) {
            throw new Exception("Pterodactyl configuration missing");
        }
        
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->baseUrl($this->url . '/api/application');
    }

    public function createUser(string $email, string $username, string $firstName, string $lastName)
    {
        $response = $this->client()->post('/users', [
            'email' => $email,
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'language' => 'en',
            'root_admin' => false,
        ]);

        if ($response->failed()) {
            throw new Exception("Failed to create user on Pterodactyl: " . $response->body());
        }

        return $response->json()['attributes'];
    }

    public function listNodes()
    {
        return $this->client()->get('/nodes')->json()['data'];
    }

    public function listEggs(int $nestId = 1) 
    {
        // Assuming nest ID 1 for Minecraft, can be made dynamic
        return $this->client()->get("/nests/{$nestId}/eggs")->json()['data'];
    }

    public function createServer(array $data)
    {
        $response = $this->client()->post('/servers', [
            'name' => $data['name'],
            'user' => $data['user_id'], // Pterodactyl User ID
            'egg' => $data['egg_id'],
            'docker_image' => $data['docker_image'],
            'startup' => $data['startup'],
            'environment' => $data['environment'],
            'limits' => [
                'memory' => $data['memory'],
                'swap' => 0,
                'disk' => $data['disk'],
                'io' => 500,
                'cpu' => $data['cpu'],
            ],
            'feature_limits' => [
                'databases' => 0,
                'allocations' => 0,
                'backups' => 0
            ],
        ]);

        if ($response->failed()) {
            throw new Exception("Failed to create server on Pterodactyl: " . $response->body());
        }

        return $response->json()['attributes'];
    }
}
