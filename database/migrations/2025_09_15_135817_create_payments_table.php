<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->enum('gateway', ['stripe', 'paypal']);
            $table->string('gateway_transaction_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('type', ['subscription', 'one_time', 'refund']);
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled', 'refunded']);
            $table->text('description')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('invoice_data')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('gateway_transaction_id');
            $table->index('transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
