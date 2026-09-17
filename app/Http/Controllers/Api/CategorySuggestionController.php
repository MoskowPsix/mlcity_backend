<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategorySuggestion\StoreCategorySuggestionRequest;
use App\Models\CategorySuggestion;

class CategorySuggestionController extends Controller
{
    public function store(StoreCategorySuggestionRequest $request): \Illuminate\Http\JsonResponse
    {
        $suggestion = CategorySuggestion::create($request->validated());

        return response()->json([
            'status' => 'success',
            'suggestion' => $suggestion,
        ], 201);
    }
}
