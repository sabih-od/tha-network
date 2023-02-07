<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('like.likes_table'), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid(config('like.user_foreign_key'))->index()->comment('user_id');
            $table->uuidMorphs('likeable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(config('like.likes_table'));
    }
}
