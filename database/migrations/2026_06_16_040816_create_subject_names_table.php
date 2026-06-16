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
        Schema::create('subject_names', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->nullable()->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_name_id')->nullable()->after('id');
            $table->foreign('subject_name_id')->references('id')->on('subject_names')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_names');
    }
};
