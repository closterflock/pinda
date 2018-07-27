<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auth_tokens', function (Blueprint $table) {
            $table
                ->integer('user_id')
                ->unsigned()
                ->change();
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
        });

        Schema::table('links', function (Blueprint $table) {
            $table
                ->integer('user_id')
                ->unsigned()
                ->change();
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auth_tokens', function (Blueprint $table) {
            $table->dropForeign('auth_tokens_user_id_foreign');
        });

        Schema::table('links', function (Blueprint $table) {
            $table->dropForeign('links_user_id_foreign');
        });
    }
}
