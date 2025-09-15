<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('website_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['downtime', 'uptime', 'ssl_expiry', 'domain_expiry', 'slow_response']);
            $table->enum('severity', ['low', 'medium', 'high', 'critical']);
            $table->string('title');
            $table->text('message');
            $table->json('details')->nullable();
            $table->enum('status', ['sent', 'pending', 'failed'])->default('pending');
            $table->json('channels')->nullable(); // email, sms, webhook, slack
            $table->timestamp('triggered_at');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['website_id', 'type']);
            $table->index(['user_id', 'status']);
            $table->index('triggered_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_alerts');
    }
};
