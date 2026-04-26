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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('breed');
            $table->enum('species', ['Dog', 'Cat', 'Rabbit', 'Hamster', 'Bird', 'Other']);
            $table->enum('size', ['Small', 'Medium', 'Large', 'Extra Large']);
            $table->integer('age_months')->default(0); // Age in months
            $table->enum('gender', ['Male', 'Female', 'Unknown']);
            
            // Energy Level (0-10 scale)
            $table->integer('energy_level')->default(5)->comment('Scale: 0 (sedentary) - 10 (highly active)');
            
            // Health Status
            $table->enum('health_status', ['Excellent', 'Good', 'Fair', 'Poor', 'Medical Attention Required'])
                  ->default('Good')
                  ->comment('Overall health condition of the pet');
            
            // Adoption Status (ENUM)
            $table->enum('adoption_status', ['Available', 'Pending', 'Adopted', 'Not Available', 'On Hold'])
                  ->default('Available')
                  ->comment('Current adoption status of the pet');
            
            $table->text('description')->nullable();
            $table->json('temperament')->nullable(); // e.g., ["Friendly", "Playful", "Gentle"]
            $table->json('dietary_requirements')->nullable();
            $table->text('medical_notes')->nullable();
            $table->date('intake_date')->nullable();
            $table->date('adopted_date')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
            
            // Indexes for frequent queries
            $table->index('adoption_status');
            $table->index('species');
            $table->index('energy_level');
            $table->index('health_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
