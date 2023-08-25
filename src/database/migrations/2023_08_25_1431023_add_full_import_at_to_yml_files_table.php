<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullImportAtToYmlFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yml_files', function (Blueprint $table) {
            $table->dateTime("full_import_at")
                ->nullable()
                ->after("started_at")
                ->comment("Время полного импорта");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yml_files', function (Blueprint $table) {
            $table->dropColumn('full_import_at');
        });
    }
}
