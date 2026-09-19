<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();

            // Contact details
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();

            // Localization & Settings
            $table->string('currency', 10)->default('IDR');
            $table->string('timezone', 50)->default('Asia/Jakarta');
            $table->decimal('tax_percentage', 5, 2)->default(11.00);
            $table->enum('tax_mode', ['inclusive', 'exclusive'])->default('exclusive');
            $table->boolean('tax_enabled')->default(true);
            $table->decimal('service_charge_percentage', 5, 2)->default(0.00);
            $table->boolean('service_charge_enabled')->default(false);

            // SaaS flags
            $table->boolean('trial_used')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
