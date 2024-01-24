<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function getCategories();
    public function createCategory(array $categoryDetails);
    public function updateCategory($categoryId, array $categoryDetails);
    public function deleteCategory($categoryId);
}
