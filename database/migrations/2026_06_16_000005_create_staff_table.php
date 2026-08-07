<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();          // e.g. Master Stylist
            $table->string('role')->default('stylist');   // stylist, beautician, makeup_artist, manager, receptionist, cashier
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('skills')->nullable();
            $table->integer('experience_years')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->boolean('is_public')->default(true);  // show on website team section
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
