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
        Schema::create('sites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->text('address')->nullable();
            $table->string('timezone', 50)->default('Africa/Lubumbashi');
            $table->boolean('active')->default(true);

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->index(['organization_id', 'active']);
            $table->index(['latitude', 'longitude']);
        });

        Schema::create('cameras', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('code', 50)->unique();
            $table->text('stream_url_encrypted');
            $table->string('protocol', 20)->default('rtsp');
            $table->decimal('direction_degrees', 5, 2)->nullable();
            $table->string('status', 20)->default('offline');
            $table->json('capabilities')->nullable();
            $table->timestampTz('last_seen_at')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->index(['site_id', 'status']);
            $table->index(['status', 'last_seen_at']);
            $table->index(['latitude', 'longitude']);
        });

        Schema::create('camera_status_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('camera_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20);
            $table->json('details')->nullable();
            $table->timestampTz('recorded_at');

            $table->index(['camera_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camera_status_logs');
        Schema::dropIfExists('cameras');
        Schema::dropIfExists('sites');
    }
};
