<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToLinksAndTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $addSoftDelete = function (Blueprint $table) {
            $table->softDeletes();
        };
        Schema::table('links', $addSoftDelete);
        Schema::table('tags', $addSoftDelete);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $removeSoftDelete = function (Blueprint $table) {
            $table->dropSoftDeletes();
        };
        Schema::table('links', $removeSoftDelete);
        Schema::table('tags', $removeSoftDelete);
    }
}
