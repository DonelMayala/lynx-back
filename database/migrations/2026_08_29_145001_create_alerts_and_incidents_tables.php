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
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('rule_type', 50);
            $table->json('conditions');
            $table->string('severity', 20);
            $table->boolean('active')->default(true);
            $table->timestampTz('valid_from')->nullable();
            $table->timestampTz('valid_until')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'active']);
            $table->index(['rule_type', 'active']);
        });

        Schema::create('alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('alert_rule_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('detection_id')->nullable()->constrained()->nullOnDelete();
            $table->string('severity', 20);
            $table->string('status', 20)->default('new');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestampTz('triggered_at');
            $table->timestampTz('acknowledged_at')->nullable();
            $table->timestampTz('resolved_at')->nullable();
            $table->text('resolution_note')->nullable();
            $table->timestamps();

            $table->index(['status', 'triggered_at']);
            $table->index(['severity', 'triggered_at']);
            $table->index(['latitude', 'longitude']);
        });

        Schema::create('incidents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('alert_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('reference_number', 40)->unique();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('priority', 20);
            $table->string('status', 20)->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('opened_at');
            $table->timestampTz('closed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority', 'opened_at']);
            $table->index(['assigned_to', 'status']);
        });

        Schema::create('incident_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('incident_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type', 50);
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestampTz('created_at');

            $table->index(['incident_id', 'created_at']);
        });

        Schema::create('evidence', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('incident_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('recording_id')->nullable()->constrained('video_recordings')->nullOnDelete();
            $table->string('evidence_type', 30);
            $table->text('storage_key');
            $table->string('checksum', 128);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['incident_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence');
        Schema::dropIfExists('incident_events');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('alert_rules');
    }
};
