<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAuthorColumnFromNewsTable extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('author');  // This will drop the 'author' column
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('author')->nullable(); // This will add back the 'author' column if you roll back
        });
    }
}
