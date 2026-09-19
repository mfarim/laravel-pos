<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTenantAndSaasFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid', 36)->nullable()->unique()->after('id');
            $table->unsignedInteger('tenant_id')->nullable()->after('uuid');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('pin', 60)->nullable()->after('password'); // hashed PIN for supervisor override
            $table->string('api_token', 80)->nullable()->unique()->after('pin');
            $table->boolean('is_superadmin')->default(false)->after('api_token');
            $table->string('locale', 10)->default('id')->after('is_superadmin');

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['uuid', 'tenant_id', 'phone', 'pin', 'api_token', 'is_superadmin', 'locale']);
        });
    }
}
