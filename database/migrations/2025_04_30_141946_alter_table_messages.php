<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_id')->nullable();
            $table->tinyInteger('media_type')->default(0); // 0-text, 1-image, 2-video, 3-audio, 4-file
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
