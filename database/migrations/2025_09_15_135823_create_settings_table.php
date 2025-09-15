<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type', 50)->default('string'); // string, integer, boolean, json, array
            $table->text('description')->nullable();
            $table->string('group', 50)->default('general');
            $table->boolean('is_public')->default(false); // Can be accessed from frontend
            $table->timestamps();

            $table->index(['group', 'key']);
            $table->index('is_public');
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
