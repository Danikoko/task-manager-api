<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\CategoryRepositoryInterface;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories()
    {
        return $this->categoryRepository->getCategories();
    }

    public function getSingleCategory(Category $category)
    {
        return $this->categoryRepository->getSingleCategory($category->id);
    }

    public function createCategory(CategoryRequest $request)
    {
        $validatedData = $request->validated();
        return $this->categoryRepository->createCategory($validatedData);
    }

    public function updateCategory(CategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();
        return $this->categoryRepository->updateCategory($category->id, $validatedData);
    }

    public function deleteCategory(Category $category)
    {
        return $this->categoryRepository->deleteCategory($category->id);
    }
}
