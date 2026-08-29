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
        Schema::create('video_recordings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('camera_id')->constrained()->cascadeOnDelete();
            $table->string('storage_key', 255)->unique();
            $table->timestampTz('started_at');
            $table->timestampTz('ended_at')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('checksum', 128)->nullable();
            $table->timestampTz('retention_until')->nullable();
            $table->string('status', 20)->default('recording');
            $table->timestamps();

            $table->index(['camera_id', 'started_at']);
            $table->index(['status', 'retention_until']);
        });

        Schema::create('detections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('camera_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('recording_id')->nullable()->constrained('video_recordings')->nullOnDelete();
            $table->string('detection_type', 50);
            $table->decimal('confidence', 5, 4);
            $table->timestampTz('detected_at');
            $table->json('bounding_box')->nullable();
            $table->text('snapshot_key')->nullable();
            $table->json('model_metadata')->nullable();
            $table->string('validation_status', 20)->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('validated_at')->nullable();
            $table->timestamps();

            $table->index(['camera_id', 'detected_at']);
            $table->index(['detection_type', 'detected_at']);
            $table->index(['validation_status', 'detected_at']);
        });

        Schema::create('detected_objects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('detection_id')->constrained()->cascadeOnDelete();
            $table->string('object_type', 30);
            $table->string('local_track_id', 100)->nullable();
            $table->decimal('confidence', 5, 4)->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();

            $table->index(['object_type', 'created_at']);
            $table->index(['detection_id', 'local_track_id']);
        });

        Schema::create('plate_readings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('detected_object_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('plate_number_encrypted');
            $table->string('normalized_plate_hash', 128);
            $table->string('country_code', 3)->nullable();
            $table->decimal('confidence', 5, 4);
            $table->string('validation_status', 20)->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('validated_at')->nullable();
            $table->timestamps();

            $table->index('normalized_plate_hash');
            $table->index(['validation_status', 'created_at']);
        });

        Schema::create('tracked_objects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('object_type', 30);
            $table->string('reference_code', 50)->unique();
            $table->json('characteristics')->nullable();
            $table->timestampTz('first_seen_at');
            $table->timestampTz('last_seen_at');
            $table->timestamps();

            $table->index(['object_type', 'last_seen_at']);
        });

        Schema::create('object_observations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tracked_object_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('detected_object_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('camera_id')->constrained()->cascadeOnDelete();
            $table->decimal('match_confidence', 5, 4);
            $table->timestampTz('observed_at');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->unique(['tracked_object_id', 'detected_object_id']);
            $table->index(['tracked_object_id', 'observed_at']);
            $table->index(['camera_id', 'observed_at']);
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('object_observations');
        Schema::dropIfExists('tracked_objects');
        Schema::dropIfExists('plate_readings');
        Schema::dropIfExists('detected_objects');
        Schema::dropIfExists('detections');
        Schema::dropIfExists('video_recordings');
    }
};
