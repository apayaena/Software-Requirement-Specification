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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('site_name', 100);
            $table->enum('area_type', ['Pit', 'Hauling', 'Disposal', 'Workshop', 'Office', 'Port']);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });

        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 30)->unique();
            $table->foreignId('reporter_id')->constrained('users');
            $table->foreignId('location_id')->constrained('locations');
            $table->enum('category', ['KTA', 'TTA', 'Near Miss', 'Accident']);
            $table->enum('status', ['Open', 'In Review', 'Investigating', 'CAPA Progress', 'Verifying', 'Closed'])->default('Open');
            $table->enum('severity', ['Low', 'Medium', 'High', 'Critical'])->nullable();
            $table->dateTime('incident_date');
            $table->timestamps();

            // Indexes for Performance Optimization
            $table->index(['status', 'severity']);
            $table->index('ticket_number');
        });

        Schema::create('incident_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->cascadeOnDelete();
            $table->text('description');
            $table->text('initial_action')->nullable();
            $table->string('photo_before');
            $table->text('root_cause')->nullable(); // RCA 5 Whys
            $table->timestamps();
        });

        Schema::create('action_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents');
            $table->foreignId('assigned_department_id')->constrained('departments');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users');
            $table->text('task_description');
            $table->date('due_date');
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Overdue'])->default('Pending');
            $table->string('photo_after')->nullable();
            $table->text('completion_notes')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents');
            $table->foreignId('user_id')->constrained('users');
            $table->string('action', 100);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('action_tasks');
        Schema::dropIfExists('incident_details');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('locations');
    }
};
