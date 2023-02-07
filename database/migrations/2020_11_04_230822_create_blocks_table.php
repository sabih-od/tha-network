<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            config('block.table_names.blocks'),
            function (Blueprint $table): void {
                config('block.uuids') ? $table->uuid('uuid')->primary() : $table->id('id');
                $table->foreignUuid(config('block.column_names.user_foreign_key'))
                    ->index()
                    ->comment('user_id')
                    ->references('id')
                    ->on('users');
                $table->uuidMorphs('blockable');
                $table->timestamps();
                $table->unique([config('block.column_names.user_foreign_key'), 'blockable_type', 'blockable_id']);
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('block.table_names.blocks'));
    }
}
