<?php

namespace Tests\Feature;

use App\Models\Detection;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DetectionWorkflowTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_active_operator_can_confirm_pending_detection(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $detection = Detection::factory()->create(['validation_status' => 'pending']);

        $this->actingAs($user)
            ->patch(route('detections.update', $detection), ['validation_status' => 'confirmed'])
            ->assertSessionHas('status', 'Détection examinée.');

        $this->assertDatabaseHas('detections', [
            'id' => $detection->id,
            'validation_status' => 'confirmed',
            'validated_by' => $user->id,
        ]);
    }

    public function test_detection_rejects_unsupported_validation_status(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $detection = Detection::factory()->create(['validation_status' => 'pending']);

        $this->actingAs($user)
            ->from(route('detections.index'))
            ->patch(route('detections.update', $detection), ['validation_status' => 'automatic_match'])
            ->assertRedirect(route('detections.index'))
            ->assertSessionHasErrors('validation_status');

        $this->assertDatabaseHas('detections', [
            'id' => $detection->id,
            'validation_status' => 'pending',
        ]);
    }
}
