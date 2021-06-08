<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id');
            $table->string('payment_mode');
            $table->string('concept')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('issue_deposit_date')->nullable();
            $table->enum('status', ['1','2'])->default(2)->comment('1=>unathorized,2=>athorized');
            $table->string('total_payment')->nullable();
            $table->string('generated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
