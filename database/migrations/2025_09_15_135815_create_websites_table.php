<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('url');
            $table->string('domain');
            $table->enum('protocol', ['http', 'https'])->default('https');
            $table->integer('port')->default(443);
            $table->integer('check_interval')->default(5); // minutes
            $table->integer('timeout')->default(30); // seconds
            $table->boolean('follow_redirects')->default(true);
            $table->string('expected_status_codes')->default('200,201,202,203,204,301,302');
            $table->text('expected_content')->nullable();
            $table->text('request_headers')->nullable();
            $table->enum('method', ['GET', 'POST', 'HEAD'])->default('GET');
            $table->text('post_data')->nullable();
            $table->boolean('verify_ssl')->default(true);
            $table->boolean('check_ssl_expiry')->default(true);
            $table->integer('ssl_expiry_reminder_days')->default(30);
            $table->boolean('monitor_dns')->default(false);
            $table->boolean('monitor_domain_expiry')->default(false);
            $table->timestamp('domain_expiry_date')->nullable();
$table->timestamp('domain_expiry_checked_at')->nullable();
            $table->integer('domain_expiry_reminder_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false);
            $table->enum('current_status', ['up', 'down', 'unknown'])->default('unknown');
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('last_uptime_at')->nullable();
            $table->timestamp('last_downtime_at')->nullable();
            $table->integer('response_time')->nullable(); // milliseconds
            $table->integer('uptime_percentage')->default(100); // last 24h
            $table->integer('consecutive_failures')->default(0);
            $table->string('failure_reason')->nullable();
            $table->json('contact_groups')->nullable(); // email addresses for alerts
            $table->json('tags')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('domain');
            $table->index('current_status');
            $table->index('last_checked_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('websites');
    }
};
