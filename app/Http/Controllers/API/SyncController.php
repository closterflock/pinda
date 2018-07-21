<?php


namespace App\Http\Controllers\API;


use App\Http\Requests\SyncRequest;
use App\Services\LocalDataSync;
use Illuminate\Http\Response;

class SyncController extends APIController
{
    /**
     * Retrieves newly-updated data since last sync.
     *
     * @route /api/v1/sync
     * @method GET
     * @param SyncRequest $request
     * @param LocalDataSync $localDataSync
     * @return Response
     */
    public function syncData(SyncRequest $request, LocalDataSync $localDataSync)
    {
        $timestamp = $request->timestamp;

        return $this->successResponse('Success', $localDataSync->getDataToSync($request->user(), $timestamp));
    }
}