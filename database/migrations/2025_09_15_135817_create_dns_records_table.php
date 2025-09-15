<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dns_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->string('domain');
            $table->enum('record_type', ['A', 'AAAA', 'CNAME', 'MX', 'NS', 'TXT', 'SOA']);
            $table->string('name');
            $table->text('value');
            $table->integer('ttl')->nullable();
            $table->integer('priority')->nullable(); // For MX records
            $table->timestamp('last_checked_at');
            $table->timestamps();

            $table->index(['website_id', 'record_type']);
            $table->index('domain');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dns_records');
    }
};
