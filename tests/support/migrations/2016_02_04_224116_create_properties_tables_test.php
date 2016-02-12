<?php

use \Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTablesTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'properties',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('entity');
                $table->string('name');
                $table->string('type');
                $table->boolean('multiple');
                $table->string('default_value')->nullable();
                $table->timestamps();

                $table->unique(['entity', 'name']);
            }
        );
        Schema::create(
            'property_values',
            function (Blueprint $table) {
                $table->integer('property_id', false, true);
                $table->integer('entity_id', false, true);
                $table->text('value')->nullable();

                $table->index(['property_id', 'entity_id']);
                $table->foreign('property_id')
                    ->references('id')->on('properties')
                    ->onDelete('cascade');

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
        Schema::drop('property_values');
        Schema::drop('properties');
    }
}
