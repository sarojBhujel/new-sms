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
        Schema::create('fee_names', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('type')->nullable();
            $table->string('months')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        if (Schema::hasTable('fees')) {
            Schema::table('fees', function (Blueprint $table) {
                if (! Schema::hasColumn('fees', 'fee_name_id')) {
                    $table->unsignedBigInteger('fee_name_id');
                    $table->foreign('fee_name_id')->references('id')->on('fee_names');
                }
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_names');
        if (Schema::hasColumn('fees', 'fee_name_id')) {
            Schema::table('fees', function (Blueprint $table) {
                $table->dropForeign(['fee_name_id']);
                $table->dropColumn('fee_name_id');
            });
        }
    }
};
