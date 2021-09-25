<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelativeHumiditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relative_humidities', function (Blueprint $table) {
            $table->id();
            $table->string('station', 25);
            $table->timestamp('ts');
            $table->string('uom', 25);
            $table->integer('observed'/*, false, true*/);
            $table->unique(['station', 'ts']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relative_humidities');
    }
}
