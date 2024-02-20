<?php

namespace App\Repositories\CategoryProduct;

interface CategoryRepositoryInterface
{
    public function getCategory($where, $search);
    public function createCategory($search);
    public function updateCategory($search);
    public function deleteCategory($search);
    public function count();
    public function total();
}
