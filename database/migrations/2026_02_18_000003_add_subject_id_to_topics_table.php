<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectIdToTopicsTable extends Migration
{
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->after('topic_id');
        });
    }

    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('subject_id');
        });
    }
}
