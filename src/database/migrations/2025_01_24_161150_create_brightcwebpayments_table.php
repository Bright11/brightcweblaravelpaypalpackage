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
        Schema::create('brightcwebpayments', function (Blueprint $table) {
            $table->id();
            $table->string('payer_name');
            $table->string('payment_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('paid_amount', 8, 2);
            $table->string('payment_type');
            $table->string('currency');
            $table->string('payment_status');
            $table->string("payment_channel")->nullable(true);
            $table->string("bank")->nullable(true);
            $table->string("card_type")->default("No card");
            $table->string("card_no")->nullable(true);
            $table->softDeletes();
            $table->boolean('is_emailed')->default(false);
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brightcwebpayments');
    }
};
