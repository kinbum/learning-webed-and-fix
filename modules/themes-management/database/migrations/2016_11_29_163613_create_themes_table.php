<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alias', 100)->unique();
            $table->tinyInteger('enabled', false, true)->default(0);
            $table->tinyInteger('installed', false, true)->default(0);
            $table->string('installed_version', 255)->nullable();
        });

        Schema::create('theme_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('theme_id', false)->unsigned();
            $table->string('key', 100);
            $table->text('value');

            $table->unique(['theme_id', 'key']);

            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_options');
        Schema::dropIfExists('themes');
    }
}
