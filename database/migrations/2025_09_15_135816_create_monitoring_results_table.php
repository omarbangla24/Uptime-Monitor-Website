<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monitoring_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['up', 'down', 'timeout', 'ssl_error', 'dns_error']);
            $table->integer('response_time')->nullable(); // milliseconds
            $table->integer('response_code')->nullable();
            $table->text('response_headers')->nullable();
            $table->longText('response_body')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('error_message')->nullable();
            $table->json('ssl_info')->nullable();
            $table->json('dns_info')->nullable();
            $table->json('redirect_chain')->nullable();
            $table->integer('total_time')->nullable(); // Total request time
            $table->integer('namelookup_time')->nullable();
            $table->integer('connect_time')->nullable();
            $table->integer('pretransfer_time')->nullable();
            $table->integer('starttransfer_time')->nullable();
            $table->string('location', 100)->default('server'); // monitoring location
            $table->timestamp('checked_at');
            $table->timestamps();

            $table->index(['website_id', 'checked_at']);
            $table->index(['website_id', 'status']);
            $table->index('checked_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('monitoring_results');
    }
};
