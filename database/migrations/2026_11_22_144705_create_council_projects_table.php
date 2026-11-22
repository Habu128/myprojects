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
        Schema::create('council_projects', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('ward');
            $table->text('description');
            $table->decimal('budget_amount', 15, 2)->nullable();
            $table->unsignedSmallInteger('budget_year')->nullable();
            $table->string('status')->default('planned');
            $table->string('source_title');
            $table->string('source_url', 2048);
            $table->string('evidence_url', 2048)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('council_projects');
    }
};
