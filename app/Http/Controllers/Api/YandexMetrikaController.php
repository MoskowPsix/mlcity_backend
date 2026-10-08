<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\YandexMetrikaSetting;
use Illuminate\Http\JsonResponse;

class YandexMetrikaController extends Controller
{
    public function show(): JsonResponse
    {
        $row = YandexMetrikaSetting::query()->first();
        $id = (int) ($row->counter_id ?? 0);
        $enabled = $row !== null && $row->enabled && $id > 0;

        return response()->json([
            'enabled' => $enabled,
            'id' => $enabled ? $id : null,
            'webvisor' => (bool) ($row->webvisor ?? false),
            'clickmap' => (bool) ($row->clickmap ?? false),
            'track_links' => (bool) ($row->track_links ?? false),
            'accurate_track_bounce' => (bool) ($row->accurate_track_bounce ?? false),
        ]);
    }
}
