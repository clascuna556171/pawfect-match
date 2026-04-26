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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            
            $table->enum('status', ['Submitted', 'Under Review', 'Approved', 'Rejected', 'Withdrawn'])
                  ->default('Submitted')
                  ->comment('Application review status');
            
            $table->text('home_type')->nullable(); // House, Apartment, Farm, etc.
            $table->integer('household_members')->nullable();
            $table->boolean('has_other_pets')->default(false);
            $table->text('other_pets_details')->nullable();
            $table->boolean('yard_available')->default(false);
            $table->text('experience_with_pets')->nullable();
            $table->text('reason_for_adoption')->nullable();
            $table->text('references')->nullable();
            $table->text('additional_information')->nullable();
            
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            
            // Indexes for frequent queries
            $table->index('status');
            $table->index('user_id');
            $table->index('pet_id');
            $table->index('submitted_at');
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
