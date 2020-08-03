<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->char('session_id', 255);
                $table->integer('user_id')->unsigned();
                $table->char('model', 100);
                $table->char('activity', 255);
                $table->integer('pk_id');
                $table->text('data');
                $table->char('ip_address', 100);
                $table->timestamps();
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
        Schema::dropIfExists('activity_logs');
    }
}
