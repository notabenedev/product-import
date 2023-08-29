<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
                $table->string("import_uuid")
                    ->nullable()
                    ->after("id")
                    ->comment("uuid импортированный");
                $table->string("import_category")
                    ->nullable()
                    ->after("import_uuid")
                    ->comment("uuid категории импортированный");
                $table->string("import_code")
                    ->nullable()
                    ->after("id")
                    ->comment("код импортированный");
                $table->unsignedBigInteger("yml_file_id")
                    ->nullable()
                    ->after("description")
                    ->comment("id файла импорта");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('import_uuid');
            $table->dropColumn('import_category');
            $table->dropColumn('import_code');
            $table->dropColumn('yml_file_id');
        });
    }
}
