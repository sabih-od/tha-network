<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeCodeFieldNonUniqueOnUserInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->dropUnique('user_invitations_code_unique');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->dropUnique('user_invitations_code_index');
            $table->unique('code');
        });
    }
}
