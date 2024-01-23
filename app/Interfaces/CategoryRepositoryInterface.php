<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function getCategories();
    public function createCategory($categoryDetails);
    public function updateCategory($categoryId, $categoryDetails);
    public function deleteCategory($categoryId);
}
