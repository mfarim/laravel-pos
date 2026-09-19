<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasInventoryAndCatalogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Units of measurement
        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tenant_id');
            $table->string('name', 50); // Pieces, Kilogram, Gram, Liter, Mililiter, Box
            $table->string('symbol', 20); // pcs, kg, gr, l, ml, box
            $table->boolean('is_base_unit')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index('tenant_id');
        });

        // 2. Inventory Categories (Bahan Baku / Raw Material)
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tenant_id');
            $table->string('name', 100);
            $table->string('code', 50)->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('inventory_categories')->onDelete('set null');
            $table->index('tenant_id');
        });

        // 3. Raw Materials & Inventory Items
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->string('sku', 50);
            $table->string('name');
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('minimum_stock', 12, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('inventory_categories')->onDelete('set null');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            $table->unique(['tenant_id', 'sku']);
            $table->index('tenant_id');
        });

        // 4. Inventory Stocks (Stok per Cabang / Outlet)
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('inventory_item_id');
            $table->decimal('quantity', 14, 4)->default(0);
            $table->decimal('reserved_quantity', 14, 4)->default(0);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->unique(['outlet_id', 'inventory_item_id']);
            $table->index('tenant_id');
        });

        // 5. Stock Movement Audit Trail
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('inventory_item_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->enum('type', ['in', 'out', 'transfer', 'adjustment', 'sale', 'waste'])->default('in');
            $table->decimal('before_quantity', 14, 4)->default(0);
            $table->decimal('quantity', 14, 4); // positive or negative
            $table->decimal('after_quantity', 14, 4)->default(0);
            $table->string('reference_type', 100)->nullable();
            $table->string('reference_id', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->index(['tenant_id', 'outlet_id', 'inventory_item_id']);
        });

        // 6. Menu / Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'slug']);
            $table->index('tenant_id');
        });

        // 7. Products (Menu yang dijual di POS)
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('inventory_item_id')->nullable(); // Optional direct 1:1 link to raw item
            $table->string('sku', 50);
            $table->string('barcode', 100)->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('base_price', 15, 2)->default(0);
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->enum('product_type', ['single', 'variant', 'combo'])->default('single');
            $table->boolean('track_stock')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('set null');
            $table->unique(['tenant_id', 'sku']);
            $table->index(['tenant_id', 'barcode']);
            $table->index(['tenant_id', 'category_id', 'is_active']);
        });

        // 8. Product Variants
        Schema::create('product_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('product_id');
            $table->string('sku', 50)->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('name'); // Regular, Large, Level 1, etc.
            $table->decimal('price_adjustment', 15, 2)->default(0);
            $table->unsignedInteger('inventory_item_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('set null');
            $table->index('product_id');
        });

        // 9. Modifier Groups & Modifiers (Topping, Gula, dll)
        Schema::create('modifier_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->string('name'); // Topping, Tingkat Manis
            $table->boolean('is_required')->default(false);
            $table->integer('min_selections')->default(0);
            $table->integer('max_selections')->default(1);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index('tenant_id');
        });

        Schema::create('modifiers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('modifier_group_id');
            $table->string('name'); // Boba, Keju, Normal Sugar
            $table->decimal('price', 15, 2)->default(0);
            $table->unsignedInteger('inventory_item_id')->nullable();
            $table->timestamps();

            $table->foreign('modifier_group_id')->references('id')->on('modifier_groups')->onDelete('cascade');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('set null');
            $table->index('modifier_group_id');
        });

        Schema::create('product_modifier_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('modifier_group_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('modifier_group_id')->references('id')->on('modifier_groups')->onDelete('cascade');
            $table->unique(['product_id', 'modifier_group_id']);
        });

        // 10. Recipes (Bill of Materials)
        Schema::create('recipes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('product_variant_id')->nullable();
            $table->string('name');
            $table->decimal('yield_quantity', 12, 4)->default(1);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->index(['tenant_id', 'product_id']);
        });

        Schema::create('recipe_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('recipe_id');
            $table->unsignedInteger('inventory_item_id');
            $table->decimal('quantity', 14, 4); // Misal 0.25 (kg)
            $table->unsignedInteger('unit_id')->nullable();
            $table->timestamps();

            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            $table->index('recipe_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_items');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('product_modifier_groups');
        Schema::dropIfExists('modifiers');
        Schema::dropIfExists('modifier_groups');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('inventory_stocks');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_categories');
        Schema::dropIfExists('units');
    }
}
