<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->string('menu_name', 100);
            $table->string('category', 50)->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('status', ['pending', 'processing', 'done'])->default('pending');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}