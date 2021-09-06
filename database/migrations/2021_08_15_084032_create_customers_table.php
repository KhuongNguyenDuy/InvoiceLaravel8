<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('abbreviate', 50);   //abbreviations company
            $table->string('address', 255);
            $table->string('phone', 15);
            $table->string('fax', 15)->nullable();
            $table->string('director_name', 255)->nullable();
            $table->date('establish_date')->nullable();
            $table->integer('capital')->nullable();
            $table->integer('employee_num')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
