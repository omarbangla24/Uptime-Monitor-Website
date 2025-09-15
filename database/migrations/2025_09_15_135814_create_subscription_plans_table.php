<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->enum('interval', ['monthly', 'yearly']);
            $table->integer('websites_limit')->default(0); // 0 = unlimited
            $table->integer('checks_per_minute')->default(1);
            $table->boolean('ssl_monitoring')->default(false);
            $table->boolean('dns_monitoring')->default(false);
            $table->boolean('domain_expiry_monitoring')->default(false);
            $table->boolean('email_alerts')->default(true);
            $table->boolean('sms_alerts')->default(false);
            $table->boolean('webhook_alerts')->default(false);
            $table->integer('data_retention_days')->default(30);
            $table->boolean('api_access')->default(false);
            $table->boolean('white_label')->default(false);
            $table->integer('team_members')->default(1);
            $table->json('features')->nullable();
            $table->string('stripe_plan_id')->nullable();
            $table->string('paypal_plan_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
};
