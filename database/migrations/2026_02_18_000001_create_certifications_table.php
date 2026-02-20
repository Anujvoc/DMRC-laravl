<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificationsTable extends Migration
{
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('iso_standard', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certifications');
    }
}
