<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->timestamp('domain_expiry_date')->nullable();
            $table->timestamp('domain_expiry_checked_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn(['domain_expiry_date', 'domain_expiry_checked_at']);
        });
    }
};
