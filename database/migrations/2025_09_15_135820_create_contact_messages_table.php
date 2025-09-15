<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->enum('type', ['general', 'support', 'sales', 'technical', 'billing'])->default('general');
            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Additional form fields
            $table->timestamp('replied_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_messages');
    }
};
