<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeterUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_usages', function (Blueprint $table) {
            $table->id();
            $table->string('meter', 25);
            $table->timestamp('ts');
            $table->string('uom', 25);
            $table->double('usage');
            $table->boolean('peak');
            $table->unique(['meter', 'ts']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meter_usages');
    }
}
