<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(): JsonResponse
    {
        $categories = Category::where('user_id', auth('sanctum')->id())->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ], 200);
    }

    public function createCategory(array $categoryDetails): JsonResponse
    {
        // Check if the name exists
        $existingName = Category::where('name', $categoryDetails['name'])->first();
        if ($existingName) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please use a different name.'
            ], 401);
        }

        $categoryDetails['user_id'] = auth('sanctum')->id();
        // Save the category
        $categorySaved = Category::create($categoryDetails);
        if ($categorySaved) {
            $categoryId = $categorySaved->id;
            $category = Category::findOrFail($categoryId);

            return response()->json([
                'status' => 'success',
                'message' => 'Category added successfully.'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The category couldn\'t be added.'
            ], 401);
        }
    }

    public function updateCategory($categoryId, array $categoryDetails): JsonResponse
    {
        // Check if the name exists
        $existingName = Category::where('name', $categoryDetails['name'])->first();
        if ($existingName && $existingName->id != $categoryId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please use a different name.'
            ], 401);
        }

        $category = Category::findOrFail($categoryId);
        // Check if this category belongs to the currently logged in user
        if ($category->user_id != auth('sanctum')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not own this category.'
            ], 403);
        }

        // Update the category
        $categoryUpdated = Category::whereId($categoryId)->update($categoryDetails);
        if ($categoryUpdated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully.'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The category couldn\'t be updated.'
            ], 401);
        }
    }

    public function deleteCategory($categoryId): JsonResponse
    {
        $category = Category::find($categoryId);

        // Check if this category belongs to the currently logged in user
        if ($category->user_id != auth('sanctum')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not own this category.'
            ], 403);
        }

        $categoryDestroyed = Category::destroy($categoryId);
        if ($categoryDestroyed) {

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The category couldn\'t be deleted.'
            ], 401);
        }
    }
}
