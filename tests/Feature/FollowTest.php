<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_follow_another_user(): void
    {
        $follower = User::factory()->create();
        $target   = User::factory()->create();

        $response = $this->actingAs($follower)->post("/follow/{$target->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('followers', [
            'user_id'      => $follower->id,
            'following_id' => $target->id,
        ]);
    }

    public function test_authenticated_user_can_unfollow_a_user(): void
    {
        $follower = User::factory()->create();
        $target   = User::factory()->create();

        // Establish the follow relationship first
        $follower->following()->attach($target->id);

        $response = $this->actingAs($follower)->post("/unfollow/{$target->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('followers', [
            'user_id'      => $follower->id,
            'following_id' => $target->id,
        ]);
    }

    public function test_following_a_user_twice_does_not_cause_errors(): void
    {
        $follower = User::factory()->create();
        $target   = User::factory()->create();

        $this->actingAs($follower)->post("/follow/{$target->id}");
        // A second follow attach should not throw an unhandled exception
        $response = $this->actingAs($follower)->post("/follow/{$target->id}");

        $response->assertRedirect();
    }

    public function test_unfollowing_without_existing_relationship_does_not_cause_errors(): void
    {
        $follower = User::factory()->create();
        $target   = User::factory()->create();

        // No prior follow — detach should be a no-op
        $response = $this->actingAs($follower)->post("/unfollow/{$target->id}");

        $response->assertRedirect();
    }
}
