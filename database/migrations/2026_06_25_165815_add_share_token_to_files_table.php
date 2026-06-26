<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('share_token')->unique()->nullable()->after('path');
            $table->timestamp('share_token_expires_at')->nullable()->after('share_token');
        });
    }

    //Reverse the migrations.
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['share_token', 'share_token_expires_at']);
        });
    }
};
