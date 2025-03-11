<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\MikrotikService;

class MikrotikController extends Controller
{
    protected $mikrotik;

    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    /**
     * GET /api/mikrotik/status
     * For example, return router logs as a status indicator.
     */
    public function status()
    {
        $logs = $this->mikrotik->getLogs();
        return response()->json($logs, Response::HTTP_OK);
    }
    public function getUsers()
    {
        $logs = $this->mikrotik->getUsers();
        return response()->json($logs, Response::HTTP_OK);
    }


    /**
     * GET /api/mikrotik/interfaces
     * Retrieve and return the router's network interfaces.
     */
    public function interfaces()
    {
        $interfaces = $this->mikrotik->getInterfaces();
        return response()->json($interfaces, Response::HTTP_OK);
    }

    /**
     * POST /api/mikrotik/add-user
     * Add a new PPP secret (user) to the router.
     */
    public function addUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $this->mikrotik->addUser($request->username, $request->password);
        return response()->json([
            'message' => 'User added successfully',
            'result'  => $result
        ], Response::HTTP_CREATED);
    }

    /**
     * DELETE /api/mikrotik/remove-user
     * Remove a user from the router.
     */
    public function removeUser(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $result = $this->mikrotik->removeUser($request->id);
        return response()->json([
            'message' => 'User removed successfully',
            'result'  => $result
        ], Response::HTTP_OK);
    }

    /**
     * POST /api/mikrotik/bandwidth
     * Set or update bandwidth for a given user/device.
     */
    public function setBandwidth(Request $request)
    {
        $request->validate([
            'username'    => 'required|string',
            'max_limit'   => 'required|string', // e.g., "1M/1M"
            'burst_limit' => 'nullable|string',
        ]);

        $result = $this->mikrotik->setBandwidth($request->username, $request->max_limit, $request->burst_limit);
        return response()->json([
            'message' => 'Bandwidth updated successfully',
            'result'  => $result
        ], Response::HTTP_OK);
    }
}
