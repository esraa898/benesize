<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeginKeyFromProductColorSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_color_sizes', function (Blueprint $table) {
            $table->dropForeign('product_color_sizes_product_size_id_foreign');
            $table->dropColumn('product_size_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_color_sizes', function (Blueprint $table) {
            //
        });
    }
}
