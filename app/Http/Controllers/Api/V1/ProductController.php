<?php

namespace App\Http\Controllers\Api\V1;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    /**
     * List active products with variants, modifiers, and categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $outletId = $request->get('current_outlet_id');

        $query = Product::where('is_active', true)
            ->with([
                'category',
                'variants' => function ($q) {
                    $q->where('is_active', true);
                },
                'modifierGroups.modifiers',
            ]);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('sort_order')->paginate($request->input('per_page', 50));

        // Format products with stock info if outlet context exists
        $formatted = $products->getCollection()->map(function ($product) use ($outletId) {
            $stockQty = null;
            if ($outletId && $product->inventory_item_id) {
                $stock = $product->inventoryItem ? $product->inventoryItem->stockForOutlet($outletId)->first() : null;
                $stockQty = $stock ? (float) $stock->quantity : 0;
            }

            return [
                'id'            => $product->id,
                'uuid'          => $product->uuid,
                'name'          => $product->name,
                'sku'           => $product->sku,
                'barcode'       => $product->barcode,
                'description'   => $product->description,
                'image'         => $product->image,
                'price'         => (float) $product->base_price,
                'cost_price'    => (float) $product->cost_price,
                'product_type'  => $product->product_type,
                'track_stock'   => (bool) $product->track_stock,
                'current_stock' => $stockQty,
                'category'      => $product->category ? [
                    'id'   => $product->category->id,
                    'uuid' => $product->category->uuid,
                    'name' => $product->category->name,
                ] : null,
                'variants'      => $product->variants->map(function ($v) {
                    return [
                        'id'               => $v->id,
                        'uuid'             => $v->uuid,
                        'name'             => $v->name,
                        'sku'              => $v->sku,
                        'barcode'          => $v->barcode,
                        'price_adjustment' => (float) $v->price_adjustment,
                    ];
                }),
                'modifier_groups' => $product->modifierGroups->map(function ($group) {
                    return [
                        'id'             => $group->id,
                        'uuid'           => $group->uuid,
                        'name'           => $group->name,
                        'is_required'    => (bool) $group->is_required,
                        'min_selections' => (int) $group->min_selections,
                        'max_selections' => (int) $group->max_selections,
                        'modifiers'      => $group->modifiers->map(function ($m) {
                            return [
                                'id'    => $m->id,
                                'uuid'  => $m->uuid,
                                'name'  => $m->name,
                                'price' => (float) $m->price,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return $this->successResponse([
            'items'        => $formatted,
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
            'total'        => $products->total(),
        ], 'Daftar produk berhasil diambil');
    }

    /**
     * Search products by barcode.
     *
     * @param  string  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function barcode($code)
    {
        $product = Product::where('is_active', true)
            ->where('barcode', $code)
            ->with(['category', 'variants', 'modifierGroups.modifiers'])
            ->first();

        if ($product) {
            return $this->successResponse($product, 'Produk ditemukan');
        }

        return $this->errorResponse('Produk dengan barcode tersebut tidak ditemukan', 404);
    }

    /**
     * Get single product detail.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::where(function ($q) use ($id) {
            $q->where('id', $id)->orWhere('uuid', $id);
        })->with(['category', 'variants', 'modifierGroups.modifiers'])->first();

        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
        }

        return $this->successResponse($product, 'Detail produk berhasil diambil');
    }
}
