<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAuthorColumnFromNewsTable extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('author');
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('author')->nullable();
        });
    }
}
