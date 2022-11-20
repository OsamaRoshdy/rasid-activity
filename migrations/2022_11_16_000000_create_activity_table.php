<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('activity.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('subject', 'subject');
            $table->bigInteger('user_id')->unsigned();
            $table->dateTime('log_date');
            $table->string('log_type',50);
            $table->longText('data');
            $table->ipAddress();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('activity.table'));
    }
};
