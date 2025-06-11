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
        Schema::create('internship_seekers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number')->nullable()->default('Not provided');
            $table->string('profile_photo')->nullable()->default('build/assets/images/unknown-person.jpg');
            $table->string('cover_photo')->nullable()->default('default_cover.jpg');
            $table->text('description')->nullable()->default('No bio yet.');
            $table->string('github_link')->nullable()->default('https://github.com/');
            $table->text('skills')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_seekers');
    }
};
