<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('external_job_id')->nullable();
            $table->string('title');
            $table->string('company');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('source')->nullable();
            $table->string('apply_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
