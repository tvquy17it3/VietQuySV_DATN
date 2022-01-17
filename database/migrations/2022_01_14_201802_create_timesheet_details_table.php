<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_details', function (Blueprint $table) {
            $table->id();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->integer('distance')->default(0);
            $table->decimal('accuracy', 5, 2)->default(0);
            $table->string('ip_address', 50)->nullable();
            $table->longText('img');
            $table->decimal('confidence', 5, 2)->default(0);
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreignId('timesheet_id');
            $table->timestamps();

            $table->foreign('timesheet_id')->references('id')->on('timesheets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_details');
    }
}
