<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string("import_uuid")
                ->nullable()
                ->after("id")
                ->comment("uuid импортированный");
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string("import_parent")
                ->nullable()
                ->after("import_uuid")
                ->comment("uuid родителя импортированный");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('import_uuid');
            $table->dropColumn('import_parent');
        });
    }
}
