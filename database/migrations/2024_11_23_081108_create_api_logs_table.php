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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('api_endpoint')->comment('The endpoint of the API');
            $table->string('method')->comment('The HTTP method used');
            $table->json('request_headers')->nullable()->comment('The headers sent with the request');
            $table->json('response_headers')->nullable()->comment('The headers received in the response');
            $table->json('response_body')->nullable()->comment('The body of the response');
            $table->json('request_body')->nullable()->comment('The body of the request');
            $table->string('ip')->nullable()->comment('The IP address of the client');
            $table->string('user_agent')->comment('The user agent of the client');
            $table->integer('status_code')->comment('The status code of the response');
            $table->decimal('response_time', 10, 3)->comment('Response time in milliseconds');
            $table->longText('error')->nullable()->comment('The error message if the request failed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
