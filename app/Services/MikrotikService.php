<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;

class MikrotikService
{
    protected $client;

    public function __construct()
    {
        // Instantiate the client using environment variables
        $this->client = new Client([
            'host' => env('MIKROTIK_HOST'),
            'user' => env('MIKROTIK_USER'),
            'pass' => env('MIKROTIK_PASS'),
            'port' => (int)env('MIKROTIK_PORT', 8728),
        ]);
    }

    /**
     * Retrieve router logs.
     */
    public function getLogs()
    {
        $query = new Query("/log/print");
        return $this->client->query($query)->read();
    }

    /**
     * Retrieve list of network interfaces.
     */
    public function getInterfaces()
    {
        $query = new Query("/interface/print");
        return $this->client->query($query)->read();
    }

    /**
     * Add a new PPP secret (user) to the router.
     * Adjust the command based on your Mikrotik configuration.
     */
    public function addUser(string $username, string $password)
    {
        $query = (new Query("/ppp/secret/add"))
            ->equal('name', $username)
            ->equal('password', $password)
            ->equal('service', 'pppoe'); // Example: adjust if needed
        return $this->client->query($query)->read();
    }

    public function getUsers()
    {
        // Remove by matching username (ensure this is the correct method for your config)
        $query = new Query("/ppp/secret/print");
        return $this->client->query($query)->read();
    }

    /**
     * Remove a PPP secret (user) from the router.
     */
    public function removeUser(string $id)
    {
        $query = new Query("/ppp/secret/remove");
        return $this->client->query($query->equal('.id', $id))->read();
    }

    /**
     * Update bandwidth (via simple queue, for example).
     * The parameters depend on your actual Mikrotik configuration.
     */
    public function setBandwidth(string $username, string $maxLimit, ?string $burstLimit = null)
    {
        $query = (new Query("/queue/simple/set"))
            ->equal('target', $username)  // or use an appropriate identifier
            ->equal('max-limit', $maxLimit);

        if ($burstLimit) {
            $query->equal('burst-limit', $burstLimit);
        }
        return $this->client->query($query)->read();
    }
}
