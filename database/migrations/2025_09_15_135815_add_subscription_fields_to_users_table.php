<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans')->onDelete('set null');
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->enum('subscription_status', ['active', 'inactive', 'cancelled', 'past_due', 'trialing'])->default('inactive');
            $table->string('stripe_customer_id')->nullable();
            $table->string('paypal_customer_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('timezone', 50)->default('UTC');
            $table->json('notification_settings')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->timestamp('last_activity_at')->nullable();
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan_id']);
            $table->dropColumn([
                'subscription_plan_id', 'subscription_starts_at', 'subscription_ends_at',
                'subscription_status', 'stripe_customer_id', 'paypal_customer_id',
                'trial_ends_at', 'timezone', 'notification_settings', 'is_admin',
                'last_activity_at', 'avatar', 'bio', 'company', 'website', 'phone'
            ]);
        });
    }
};
