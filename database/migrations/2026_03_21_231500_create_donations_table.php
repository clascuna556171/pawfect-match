<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pet_id')->nullable()->constrained()->nullOnDelete();
            $table->string('donor_name');
            $table->string('donor_email');
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('USD');
            $table->boolean('is_anonymous')->default(false);
            $table->string('payment_method')->default('Manual');
            $table->string('status')->default('Confirmed');
            $table->text('message')->nullable();
            $table->timestamp('donated_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'donated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
