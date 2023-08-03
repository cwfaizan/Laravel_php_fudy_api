<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pin_verifications', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('pin_type');
            $table->integer('pin');
            $table->boolean('pin_verified')->default(false);
            $table->timestamp('pin_verified_at')->nullable();
            $table->string('item');
            $table->timestamp('expired_at')->nullable();
            $table->primary(['item', 'pin_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pin_verifications');
    }
};
