<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->create(['user_id' => $user->id]);

        // The relationship is named `post` (singular) in the User model
        $this->assertCount(3, $user->post);
    }

    public function test_user_can_follow_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $userA->following()->attach($userB->id);

        $this->assertTrue($userA->isFollowing($userB));
    }

    public function test_user_is_not_following_by_default(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->assertFalse($userA->isFollowing($userB));
    }

    public function test_following_relationship_returns_correct_users(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();

        $userA->following()->attach([$userB->id, $userC->id]);

        $following = $userA->following()->pluck('users.id')->toArray();

        $this->assertContains($userB->id, $following);
        $this->assertContains($userC->id, $following);
        $this->assertCount(2, $following);
    }

    public function test_followers_relationship_returns_correct_users(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();

        $userB->following()->attach($userA->id);
        $userC->following()->attach($userA->id);

        $followers = $userA->followers()->pluck('users.id')->toArray();

        $this->assertContains($userB->id, $followers);
        $this->assertContains($userC->id, $followers);
        $this->assertCount(2, $followers);
    }

    public function test_is_following_returns_false_after_unfollow(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $userA->following()->attach($userB->id);
        $userA->following()->detach($userB->id);

        $this->assertFalse($userA->isFollowing($userB));
    }

    public function test_password_is_hidden_in_serialization(): void
    {
        $user = User::factory()->create();

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }
}
