<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('start_date_np');
            $table->string('end_date_np');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('status')->default(false)->index();
            $table->timestamps();
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiscal_years');
    }
};
