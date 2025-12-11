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
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method', 10);
            $table->text('url');
            $table->text('full_url');
            $table->string('path');
            $table->json('query_params')->nullable();
            $table->json('post_data')->nullable();
            $table->json('headers')->nullable();
            $table->text('raw_body')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->json('server_vars')->nullable();
            $table->timestamps();
            
            $table->index('method');
            $table->index('path');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
