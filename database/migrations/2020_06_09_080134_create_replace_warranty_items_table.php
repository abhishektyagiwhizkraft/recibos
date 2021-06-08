<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplaceWarrantyItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replace_warranty_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('item');
            $table->string('s_no')->nullable();
            $table->text('fault')->nullable();
            $table->string('client_mobile');
            $table->string('client_name')->nullable();
            $table->text('unique_url')->nullable();
            $table->string('description')->nullable();
            $table->integer('received_by')->nullable();
            $table->integer('replaced_by')->nullable();
            $table->enum('status',['0','1','2','3','4'])->default('0');
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
        Schema::dropIfExists('replace_warranty_items');
    }
}
