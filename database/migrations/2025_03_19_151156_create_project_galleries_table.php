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
        Schema::create('project_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('image')->nullable()->before('description');
            $table->text('attributes')->nullable()->before('description');
        });
    }
};
