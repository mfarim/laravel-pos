<?php

namespace App\Http\Controllers\Api\V1;

use App\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * List categories with product count.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = ProductCategory::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        return $this->successResponse($categories, 'Daftar kategori produk berhasil diambil');
    }

    /**
     * Get products inside a category.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function products($id)
    {
        $category = ProductCategory::where(function ($q) use ($id) {
            $q->where('id', $id)->orWhere('uuid', $id);
        })->first();

        if (!$category) {
            return $this->errorResponse('Kategori tidak ditemukan', 404);
        }

        $products = $category->products()
            ->where('is_active', true)
            ->with(['variants' => function ($q) {
                $q->where('is_active', true);
            }, 'modifierGroups.modifiers'])
            ->get();

        return $this->successResponse($products, 'Produk dalam kategori berhasil diambil');
    }
}
