<?php

namespace App\Contracts\Services\PointService;

use App\Contracts\Services\PointService\PointServiceInterface;
use App\Http\Requests\PageANDLimitRequest;
use App\Http\Requests\Point\StorePointsRequest;
use App\Models\Point;
use Exception;

class PointService implements PointServiceInterface
{

    public function store(StorePointsRequest $request): object
    {
            return Point::create([
                'user_id' => auth('api')->user()->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
    }

    public function getForUser(PageANDLimitRequest $request): object
    {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;
        return Point::where('user_id', auth('api')->user()->id)->orderBy('created_at', 'desc')->cursorPaginate($limit, ['*'], 'page' , $page);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): object
    {
        $point = Point::findOrFail($id);
        $point->user_id !== auth('api')->user()->id ? throw new Exception('This not ypu point') : null;
        return $point->delete();
    }
}
