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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_id')->constrained('brokers')->nullable()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->nullable()->cascadeOnDelete();
            $table->foreignId('installment_id')->constrained('installments')->cascadeOnDelete();
            $table->foreignId('finishe_id')->constrained('finishes')->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('water_id')->constrained('waters')->cascadeOnDelete();
            $table->foreignId('electricty_id')->constrained('electricities')->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->string('governorate');
            $table->string('city');
            $table->string('district');
            $table->string('street');
            $table->string('locationGPS');
            $table->integer('propertyNum')->nullable();
            $table->string('floorNum');
            $table->integer('flatNum')->nullable();
            $table->integer('roomNum');
            $table->integer('area');
            $table->string('ownerType');
            $table->timestamp('creationDate')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'notActive'])->default('active')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
