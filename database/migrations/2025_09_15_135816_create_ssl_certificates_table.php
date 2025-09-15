<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ssl_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->string('domain');
            $table->string('issuer')->nullable();
            $table->string('subject')->nullable();
            $table->text('fingerprint')->nullable();
            $table->text('serial_number')->nullable();
            $table->text('signature_algorithm')->nullable();
            $table->json('san_domains')->nullable(); // Subject Alternative Names
            $table->timestamp('valid_from');
            $table->timestamp('valid_to');
            $table->integer('days_until_expiry');
            $table->boolean('is_valid')->default(true);
            $table->boolean('is_self_signed')->default(false);
            $table->boolean('is_expired')->default(false);
            $table->text('validation_errors')->nullable();
            $table->timestamp('last_checked_at');
            $table->timestamps();

            $table->index(['website_id', 'domain']);
            $table->index('valid_to');
            $table->index('is_expired');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ssl_certificates');
    }
};
