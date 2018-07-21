<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Requests\SyncRequest;
use App\Services\LocalDataSync;

class SyncController extends Controller
{
    /**
     * Retrieves newly-updated data since last sync.
     *
     * @route /api/v1/sync
     * @method GET
     * @param SyncRequest $request
     * @param LocalDataSync $localDataSync
     * @return \App\Services\SyncData
     */
    public function syncData(SyncRequest $request, LocalDataSync $localDataSync)
    {
        $timestamp = $request->timestamp;

        return $localDataSync->getDataToSync($request->user(), $timestamp);
    }
}