<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosOperationsAndTransactionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Payment Methods
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tenant_id');
            $table->string('code', 50); // cash, qris, transfer, debit, credit
            $table->string('name', 100);
            $table->boolean('is_cash')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'code']);
            $table->index('tenant_id');
        });

        // 2. POS Sessions (Shift Kasir)
        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('user_id');
            $table->string('session_number', 50);
            $table->decimal('opening_cash', 15, 2);
            $table->decimal('closing_cash', 15, 2)->nullable();
            $table->decimal('expected_cash', 15, 2)->nullable();
            $table->decimal('cash_difference', 15, 2)->nullable();
            $table->text('opening_notes')->nullable();
            $table->text('closing_notes')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->unsignedInteger('closed_by')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('closed_by')->references('id')->on('users')->onDelete('set null');
            $table->unique(['outlet_id', 'session_number']);
            $table->index(['outlet_id', 'status']);
        });

        // 3. Transactions (Order Header)
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('pos_session_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->string('transaction_number', 50); // OUT01-20260919-0001
            $table->enum('type', ['sale', 'refund', 'void'])->default('sale');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('service_charge_amount', 15, 2)->default(0);
            $table->decimal('rounding', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('payment_amount', 15, 2)->default(0);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['completed', 'voided', 'pending'])->default('completed');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('pos_session_id')->references('id')->on('pos_sessions')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['tenant_id', 'transaction_number']);
            $table->index(['tenant_id', 'outlet_id', 'completed_at']);
        });

        // 4. Transaction Items (Line Items)
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('product_variant_id')->nullable();
            $table->unsignedInteger('inventory_item_id')->nullable();
            $table->string('item_name');
            $table->string('item_sku', 50)->nullable();
            $table->decimal('quantity', 12, 4);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('set null');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('set null');
            $table->index('transaction_id');
        });

        // 5. Transaction Payments (Multi-payment / Split-bill)
        Schema::create('transaction_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('payment_method_id');
            $table->decimal('amount', 15, 2);
            $table->string('reference_number')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->index('transaction_id');
        });

        // 6. Held Orders (Simpan Keranjang Belanja Sementara)
        Schema::create('held_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('pos_session_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('hold_number', 50);
            $table->string('customer_name')->nullable();
            $table->text('items'); // JSON payload of cart items
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('pos_session_id')->references('id')->on('pos_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['outlet_id', 'pos_session_id']);
        });

        // 7. Authorization Settings & Logs (Manager PIN Override)
        Schema::create('authorization_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tenant_id');
            $table->boolean('require_auth_void')->default(true);
            $table->boolean('require_auth_refund')->default(true);
            $table->boolean('require_auth_discount')->default(true);
            $table->decimal('discount_threshold_percent', 5, 2)->default(20.00); // Butuh PIN jika diskon > 20%
            $table->boolean('require_auth_price_override')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique('tenant_id');
        });

        Schema::create('authorization_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('requested_by');
            $table->unsignedInteger('authorized_by')->nullable();
            $table->enum('action_type', ['void', 'refund', 'discount', 'price_override']);
            $table->enum('status', ['approved', 'denied'])->default('approved');
            $table->string('reference_number')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('authorized_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['tenant_id', 'outlet_id', 'action_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authorization_logs');
        Schema::dropIfExists('authorization_settings');
        Schema::dropIfExists('held_orders');
        Schema::dropIfExists('transaction_payments');
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('pos_sessions');
        Schema::dropIfExists('payment_methods');
    }
}
