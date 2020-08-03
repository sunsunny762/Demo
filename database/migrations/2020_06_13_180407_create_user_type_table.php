<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_types')) {
            Schema::create('user_types', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->char('title', 255);
                $table->tinyInteger('status')->default(1);
                $table->integer('created_by')->unsigned();
                $table->integer('updated_by')->unsigned();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
}
