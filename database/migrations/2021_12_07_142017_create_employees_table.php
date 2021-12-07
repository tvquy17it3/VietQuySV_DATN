<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 12)->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['F', 'M'])->nullable();
            $table->date('birth_date')->nullable();
            $table->date('from_date')->nullable();
            $table->decimal('salary', 10, 2)->default(0);
            $table->foreignId('user_id')->unique();
            $table->foreignId('position_id');
            $table->foreignId('department_id');

            $table->timestamps();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
