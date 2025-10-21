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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique(); // TRX-XXXXX
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->enum('type', ['subscription', 'one_time', 'refund'])->default('one_time');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->json('payment_details')->nullable();
            $table->string('description');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create subscriptions table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('plan_name'); // free, professional, enterprise
            $table->decimal('price', 10, 2);
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create subscription_features table
        Schema::create('subscription_features', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->string('feature_name');
            $table->string('feature_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_features');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('transactions');
    }
};