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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id(); //Primary Key
            $table->string('Name');
            $table->string('Institution_Name');
            $table -> text('Location');
            $table -> text('Description')->nullable();
            $table->integer('Capacity');
            $table-> string('Facility_Image')->nullable();
            $table -> decimal('Daily_Cost',8,2);
            $table -> string('Category');
            $table->timestamps();
             // Adding the unique constraint
            $table->unique(['Name','Institution_Name', 'Location', 'Category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
