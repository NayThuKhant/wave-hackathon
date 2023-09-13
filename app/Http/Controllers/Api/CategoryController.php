<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Categories have been retrieved',
            'data' => Category::all(),
        ]);
    }

    public function show(Category $category)
    {
        return response()->json([
            'message' => 'Category has been retrieved',
            'data' => $category->load('services'),
        ]);
    }
}
