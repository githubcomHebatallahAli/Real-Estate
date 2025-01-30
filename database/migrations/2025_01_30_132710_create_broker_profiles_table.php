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
        Schema::create('broker_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('commission');
            $table->text('brief')->nullable();
            $table->string('targetPlace');
            $table->string('realEstateType');
            $table->string('photo')->nullable();
            $table->string('birthDate')->nullable();
            $table->string('purchaseOperations')->nullable();
            $table->string('sellingOperations')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broker_profiles');
    }
};
