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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_post_id')->constrained('internship_posts')->onDelete('cascade');
            $table->foreignId('internship_seeker_id')->constrained('internship_seekers')->onDelete('cascade');
            $table->string('status')->default('applied');
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            // To prevent duplicate applications from same seeker to same post:
            $table->unique(['internship_post_id', 'internship_seeker_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
