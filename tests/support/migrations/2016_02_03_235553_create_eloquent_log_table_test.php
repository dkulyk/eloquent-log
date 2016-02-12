<?php

use \Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEloquentLogTableTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'eloquent_log',
            function (Blueprint $table) {
                $table->increments('id');
                $table->tinyInteger('type', false, true);
                $table->integer('owner_id', false, true)->nullable();
                $table->string('object_type', 255);
                $table->string('object_id', 64);
                $table->text('data');
                $table->timestamp('created_at');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('eloquent_log');
    }
}
