<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocNoAndDate2ToModulesTable extends Migration
{
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'module_doc_no')) {
                $table->string('module_doc_no', 255)->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('modules', 'date2')) {
                $table->date('date2')->nullable()->after('form_date');
            }
        });
    }

    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            if (Schema::hasColumn('modules', 'date2')) {
                $table->dropColumn('date2');
            }
            if (Schema::hasColumn('modules', 'module_doc_no')) {
                $table->dropColumn('module_doc_no');
            }
        });
    }
}
