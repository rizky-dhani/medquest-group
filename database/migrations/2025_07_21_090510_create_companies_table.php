<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->uuid('companyId')->unique();
            $table->string('company_logo')->nullable();
            $table->string('name');
            $table->string('initials', 5)->nullable();
            $table->timestamps();
        });
        Schema::table('it_assets', function (Blueprint $table) {
            $table->foreignId('company_id')->after('asset_user_id')->nullable()->constrained('companies')->onDelete('cascade');
        });
        Schema::table('it_asset_usage_histories', function (Blueprint $table) {
            $table->foreignId('company_id')->after('usageId')->nullable()->constrained('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
