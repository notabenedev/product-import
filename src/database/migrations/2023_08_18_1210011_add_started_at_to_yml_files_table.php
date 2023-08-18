<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartedAtToYmlFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yml_files', function (Blueprint $table) {
            $table->dateTime("started_at")
                ->nullable()
                ->after("original_name")
                ->comment("Дата начала парсинга");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cml_files', function (Blueprint $table) {
            $table->dropColumn('started_at');
        });
    }
}
