<?php

use App\AuthorizationSetting;
use App\InventoryCategory;
use App\InventoryItem;
use App\InventoryStock;
use App\Modifier;
use App\ModifierGroup;
use App\Outlet;
use App\PaymentMethod;
use App\Product;
use App\ProductCategory;
use App\ProductVariant;
use App\Role;
use App\Subscription;
use App\SubscriptionPlan;
use App\Tenant;
use App\Unit;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SaasDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Subscription Plans
        $starter = SubscriptionPlan::firstOrCreate(['slug' => 'starter'], [
            'name'          => 'Starter',
            'description'   => 'Cocok untuk toko kecil dan usaha pemula 1 cabang.',
            'price_monthly' => 99000,
            'price_yearly'  => 990000,
            'max_outlets'   => 1,
            'max_users'     => 2,
            'max_products'  => 100,
            'features'      => [
                'pos_core', 'basic_reports', 'customer_management',
            ],
            'is_active'     => true,
            'sort_order'    => 1,
        ]);

        $growth = SubscriptionPlan::firstOrCreate(['slug' => 'growth'], [
            'name'          => 'Growth',
            'description'   => 'Untuk bisnis berkembang yang memiliki hingga 3 cabang.',
            'price_monthly' => 299000,
            'price_yearly'  => 2990000,
            'max_outlets'   => 3,
            'max_users'     => 5,
            'max_products'  => 500,
            'features'      => [
                'pos_core', 'multi_outlet', 'inventory_basic', 'basic_reports',
                'customer_management', 'multi_payment', 'discounts',
            ],
            'is_active'     => true,
            'sort_order'    => 2,
        ]);

        $pro = SubscriptionPlan::firstOrCreate(['slug' => 'professional'], [
            'name'          => 'Professional',
            'description'   => 'Fitur lengkap F&B dan retail dengan resep BOM, QR order, dan KDS.',
            'price_monthly' => 599000,
            'price_yearly'  => 5990000,
            'max_outlets'   => 10,
            'max_users'     => 15,
            'max_products'  => 2000,
            'features'      => [
                'pos_core', 'multi_outlet', 'inventory_basic', 'inventory_advanced',
                'recipe_bom', 'stock_transfer', 'manager_authorization', 'table_management',
                'qr_order', 'kds', 'api_access', 'multi_payment', 'discounts',
            ],
            'is_active'     => true,
            'sort_order'    => 3,
        ]);

        $enterprise = SubscriptionPlan::firstOrCreate(['slug' => 'enterprise'], [
            'name'          => 'Enterprise',
            'description'   => 'Skala waralaba dan enterprise dengan cabang tanpa batas & SLA prioritas.',
            'price_monthly' => 1499000,
            'price_yearly'  => 14990000,
            'max_outlets'   => 999,
            'max_users'     => 999,
            'max_products'  => 99999,
            'features'      => [
                'pos_core', 'multi_outlet', 'inventory_basic', 'inventory_advanced',
                'recipe_bom', 'stock_transfer', 'manager_authorization', 'table_management',
                'qr_order', 'kds', 'api_access', 'custom_branding', 'dedicated_support',
            ],
            'is_active'     => true,
            'sort_order'    => 4,
        ]);

        // 2. Seed Default Tenant: Toko Aisyah
        $tenant = Tenant::firstOrCreate(['code' => 'AISYAH'], [
            'name'                      => 'Toko Aisyah',
            'slug'                      => 'toko-aisyah',
            'email'                     => 'admin@aisyah.com',
            'phone'                     => '08123456789',
            'address'                   => 'Jl. Raya Sudirman No. 12, Jakarta',
            'currency'                  => 'IDR',
            'timezone'                  => 'Asia/Jakarta',
            'tax_percentage'            => 11.00,
            'tax_mode'                  => 'exclusive',
            'tax_enabled'               => true,
            'service_charge_percentage' => 0.00,
            'service_charge_enabled'    => false,
            'trial_used'                => true,
            'is_active'                 => true,
        ]);

        // 3. Seed Tenant Subscription (Professional Plan Active)
        Subscription::firstOrCreate(['tenant_id' => $tenant->id], [
            'subscription_plan_id' => $pro->id,
            'billing_cycle'        => 'monthly',
            'status'               => 'active',
            'starts_at'            => Carbon::now()->subDays(5),
            'ends_at'              => Carbon::now()->addYear(),
        ]);

        // 4. Seed Outlets
        $outletPusat = Outlet::firstOrCreate([
            'tenant_id' => $tenant->id,
            'code'      => 'OUT01',
        ], [
            'name'           => 'Outlet Pusat (Aisyah 01)',
            'address'        => 'Jl. Raya Sudirman No. 12, Jakarta Pusat',
            'city'           => 'Jakarta Pusat',
            'phone'          => '021-5551234',
            'email'          => 'outlet.pusat@aisyah.com',
            'opening_time'   => '08:00:00',
            'closing_time'   => '22:00:00',
            'tax_percentage' => 11.00,
            'receipt_header' => "TOKO AISYAH - OUTLET PUSAT\nJl. Raya Sudirman No. 12, Jakarta\nTelp: 021-5551234",
            'receipt_footer' => "Terima Kasih Atas Kunjungan Anda!\nBarang yang sudah dibeli tidak dapat ditukar.",
            'is_active'      => true,
        ]);

        $outletCabang = Outlet::firstOrCreate([
            'tenant_id' => $tenant->id,
            'code'      => 'OUT02',
        ], [
            'name'           => 'Outlet Cabang Tebet',
            'address'        => 'Jl. Tebet Raya No. 45, Jakarta Selatan',
            'city'           => 'Jakarta Selatan',
            'phone'          => '021-5555678',
            'email'          => 'outlet.tebet@aisyah.com',
            'opening_time'   => '09:00:00',
            'closing_time'   => '23:00:00',
            'tax_percentage' => 11.00,
            'receipt_header' => "TOKO AISYAH - CABANG TEBET\nJl. Tebet Raya No. 45, Jakarta Selatan",
            'receipt_footer' => "Terima Kasih Atas Kunjungan Anda!",
            'is_active'      => true,
        ]);

        // 5. Seed Users
        // A. Platform Super Admin
        $superadmin = User::firstOrCreate(['email' => 'superadmin@pos.id'], [
            'name'          => 'Platform Super Admin',
            'username'      => 'superadmin',
            'phone'         => '08111111111',
            'password'      => bcrypt('SuperAdmin123'),
            'pin'           => Hash::make('999999'),
            'api_token'     => 'superadmin_test_token_' . bin2hex(random_bytes(10)),
            'is_superadmin' => true,
            'locale'        => 'id',
        ]);

        // B. Tenant Owner (Pemilik Toko Aisyah)
        $pemilikRole = Role::where('name', 'pemilik')->first();
        $owner = User::firstOrCreate(['email' => 'admin@aisyah.com'], [
            'tenant_id'     => $tenant->id,
            'name'          => 'Aisyah Owner',
            'username'      => 'admin',
            'phone'         => '08123456789',
            'password'      => bcrypt('AdminAi123'),
            'pin'           => Hash::make('123456'),
            'api_token'     => 'owner_aisyah_token_' . bin2hex(random_bytes(10)),
            'is_superadmin' => false,
            'locale'        => 'id',
        ]);
        if ($pemilikRole && !$owner->hasRole('pemilik')) {
            $owner->attachRole($pemilikRole);
        }
        $owner->outlets()->syncWithoutDetaching([
            $outletPusat->id  => ['is_default' => true],
            $outletCabang->id => ['is_default' => false],
        ]);

        // C. Cashier (Penjaga Kasir Toko Aisyah)
        $penjagaRole = Role::where('name', 'penjaga')->first();
        $cashier = User::firstOrCreate(['email' => 'kasir@aisyah.com'], [
            'tenant_id'     => $tenant->id,
            'name'          => 'Budi Kasir',
            'username'      => 'penjaga',
            'phone'         => '08129876543',
            'password'      => bcrypt('member123'),
            'pin'           => Hash::make('112233'),
            'api_token'     => 'cashier_aisyah_token_' . bin2hex(random_bytes(10)),
            'is_superadmin' => false,
            'locale'        => 'id',
        ]);
        if ($penjagaRole && !$cashier->hasRole('penjaga')) {
            $cashier->attachRole($penjagaRole);
        }
        $cashier->outlets()->syncWithoutDetaching([
            $outletPusat->id => ['is_default' => true],
        ]);

        // 6. Seed Payment Methods
        $payments = [
            ['code' => 'cash', 'name' => 'Tunai (Cash)', 'is_cash' => true],
            ['code' => 'qris', 'name' => 'QRIS (Gopay/OVO/Dana/ShopeePay)', 'is_cash' => false],
            ['code' => 'bca', 'name' => 'Transfer Bank BCA', 'is_cash' => false],
            ['code' => 'debit', 'name' => 'Kartu Debit', 'is_cash' => false],
            ['code' => 'credit', 'name' => 'Kartu Kredit', 'is_cash' => false],
        ];
        foreach ($payments as $p) {
            PaymentMethod::firstOrCreate([
                'tenant_id' => $tenant->id,
                'code'      => $p['code'],
            ], [
                'name'      => $p['name'],
                'is_cash'   => $p['is_cash'],
                'is_active' => true,
            ]);
        }

        // 7. Seed Units
        $unitPcs = Unit::firstOrCreate(['tenant_id' => $tenant->id, 'symbol' => 'pcs'], ['name' => 'Pieces', 'is_base_unit' => true]);
        $unitKg  = Unit::firstOrCreate(['tenant_id' => $tenant->id, 'symbol' => 'kg'], ['name' => 'Kilogram', 'is_base_unit' => true]);
        $unitGr  = Unit::firstOrCreate(['tenant_id' => $tenant->id, 'symbol' => 'gr'], ['name' => 'Gram', 'is_base_unit' => false]);
        $unitCup = Unit::firstOrCreate(['tenant_id' => $tenant->id, 'symbol' => 'cup'], ['name' => 'Cup', 'is_base_unit' => true]);

        // 8. Seed Inventory Categories & Items
        $catBahan = InventoryCategory::firstOrCreate(['tenant_id' => $tenant->id, 'name' => 'Bahan Baku']);
        $catKemasan = InventoryCategory::firstOrCreate(['tenant_id' => $tenant->id, 'name' => 'Kemasan']);

        $itemKopi = InventoryItem::firstOrCreate(['tenant_id' => $tenant->id, 'sku' => 'RAW-KOP-01'], [
            'category_id'   => $catBahan->id,
            'unit_id'       => $unitGr->id,
            'name'          => 'Biji Kopi Arabika Espresso',
            'cost_price'    => 200, // per gram
            'minimum_stock' => 500,
            'is_active'     => true,
        ]);

        $itemSusu = InventoryItem::firstOrCreate(['tenant_id' => $tenant->id, 'sku' => 'RAW-SUS-01'], [
            'category_id'   => $catBahan->id,
            'unit_id'       => $unitPcs->id,
            'name'          => 'Susu UHT Fresh 1 Liter',
            'cost_price'    => 18000,
            'minimum_stock' => 10,
            'is_active'     => true,
        ]);

        $itemGula = InventoryItem::firstOrCreate(['tenant_id' => $tenant->id, 'sku' => 'RAW-GUL-01'], [
            'category_id'   => $catBahan->id,
            'unit_id'       => $unitGr->id,
            'name'          => 'Sirup Gula Aren Organik',
            'cost_price'    => 50, // per gram
            'minimum_stock' => 1000,
            'is_active'     => true,
        ]);

        $itemCup = InventoryItem::firstOrCreate(['tenant_id' => $tenant->id, 'sku' => 'RAW-CUP-01'], [
            'category_id'   => $catKemasan->id,
            'unit_id'       => $unitCup->id,
            'name'          => 'Paper Cup & Sedotan 16oz',
            'cost_price'    => 1500,
            'minimum_stock' => 100,
            'is_active'     => true,
        ]);

        // Seed stock quantities in Outlet Pusat
        InventoryStock::firstOrCreate(['tenant_id' => $tenant->id, 'outlet_id' => $outletPusat->id, 'inventory_item_id' => $itemKopi->id], ['quantity' => 5000]);
        InventoryStock::firstOrCreate(['tenant_id' => $tenant->id, 'outlet_id' => $outletPusat->id, 'inventory_item_id' => $itemSusu->id], ['quantity' => 50]);
        InventoryStock::firstOrCreate(['tenant_id' => $tenant->id, 'outlet_id' => $outletPusat->id, 'inventory_item_id' => $itemGula->id], ['quantity' => 10000]);
        InventoryStock::firstOrCreate(['tenant_id' => $tenant->id, 'outlet_id' => $outletPusat->id, 'inventory_item_id' => $itemCup->id], ['quantity' => 500]);

        // 9. Seed Product Categories & Menu Items
        $catBeverage = ProductCategory::firstOrCreate(['tenant_id' => $tenant->id, 'slug' => 'minuman'], [
            'name'       => 'Kopi & Minuman Segar',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $catFood = ProductCategory::firstOrCreate(['tenant_id' => $tenant->id, 'slug' => 'makanan'], [
            'name'       => 'Makanan & Camilan',
            'sort_order' => 2,
            'is_active'  => true,
        ]);

        // Product 1: Kopi Susu Gula Aren
        $kopsu = Product::firstOrCreate(['tenant_id' => $tenant->id, 'sku' => 'KOP-001'], [
            'category_id'  => $catBeverage->id,
            'barcode'      => '899123456001',
            'name'         => 'Kopi Susu Gula Aren',
            'slug'         => 'kopi-susu-gula-aren',
            'description'  => 'Espresso double shot dipadu susu segar dan gula aren asli.',
            'base_price'   => 22000,
            'cost_price'   => 9500,
            'product_type' => 'variant',
            'track_stock'  => true,
            'inventory_item_id' => $itemKopi->id,
            'is_active'    => true,
            'sort_order'   => 1,
        ]);

        ProductVariant::firstOrCreate(['product_id' => $kopsu->id, 'sku' => 'KOP-001-REG'], [
            'name'             => 'Regular (12 oz)',
            'price_adjustment' => 0,
            'is_active'        => true,
        ]);

        ProductVariant::firstOrCreate(['product_id' => $kopsu->id, 'sku' => 'KOP-001-LRG'], [
            'name'             => 'Large (16 oz)',
            'price_adjustment' => 6000,
            'is_active'        => true,
        ]);

        // Modifier Group: Topping
        $modGroup = ModifierGroup::firstOrCreate(['tenant_id' => $tenant->id, 'name' => 'Pilihan Topping'], [
            'is_required'    => false,
            'min_selections' => 0,
            'max_selections' => 3,
        ]);
        Modifier::firstOrCreate(['modifier_group_id' => $modGroup->id, 'name' => 'Boba Brown Sugar'], ['price' => 4000]);
        Modifier::firstOrCreate(['modifier_group_id' => $modGroup->id, 'name' => 'Extra Espresso Shot'], ['price' => 5000]);
        Modifier::firstOrCreate(['modifier_group_id' => $modGroup->id, 'name' => 'Coffee Jelly'], ['price' => 3500]);
        $kopsu->modifierGroups()->syncWithoutDetaching([$modGroup->id]);

        // Product 2: Matcha Latte
        Product::firstOrCreate(['tenant_id' => $tenant->id, 'sku' => 'MTC-001'], [
            'category_id'  => $catBeverage->id,
            'barcode'      => '899123456002',
            'name'         => 'Matcha Green Tea Latte',
            'slug'         => 'matcha-latte',
            'description'  => 'Pure Uji Matcha blend dengan susu krim lembut.',
            'base_price'   => 25000,
            'cost_price'   => 11000,
            'product_type' => 'single',
            'track_stock'  => true,
            'inventory_item_id' => $itemSusu->id,
            'is_active'    => true,
            'sort_order'   => 2,
        ]);

        // Product 3: Nasi Goreng Spesial
        Product::firstOrCreate(['tenant_id' => $tenant->id, 'sku' => 'NAS-001'], [
            'category_id'  => $catFood->id,
            'barcode'      => '899123456003',
            'name'         => 'Nasi Goreng Spesial Aisyah',
            'slug'         => 'nasi-goreng-spesial',
            'description'  => 'Nasi goreng harum dengan suwiran ayam, telur ceplok, dan kerupuk udang.',
            'base_price'   => 28000,
            'cost_price'   => 12000,
            'product_type' => 'single',
            'track_stock'  => false,
            'is_active'    => true,
            'sort_order'   => 3,
        ]);

        // Product 4: Roti Bakar Coklat Keju
        Product::firstOrCreate(['tenant_id' => $tenant->id, 'sku' => 'ROT-001'], [
            'category_id'  => $catFood->id,
            'barcode'      => '899123456004',
            'name'         => 'Roti Bakar Coklat Keju',
            'slug'         => 'roti-bakar-coklat-keju',
            'description'  => 'Roti panggang mentega dengan meses coklat dan parutan keju cheddar melimpah.',
            'base_price'   => 18000,
            'cost_price'   => 7000,
            'product_type' => 'single',
            'track_stock'  => false,
            'is_active'    => true,
            'sort_order'   => 4,
        ]);

        // 10. Seed Authorization Settings
        AuthorizationSetting::firstOrCreate(['tenant_id' => $tenant->id], [
            'require_auth_void'          => true,
            'require_auth_refund'        => true,
            'require_auth_discount'      => true,
            'discount_threshold_percent' => 20.00,
            'require_auth_price_override'=> true,
        ]);

        $this->command->info('Database SaaS Multi-Tenant & Demo Toko Aisyah berhasil diisi!');
    }
}
