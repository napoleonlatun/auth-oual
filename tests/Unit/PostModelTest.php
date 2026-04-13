<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    public function test_post_has_fillable_content_and_user_id(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'content' => 'Test content',
            'user_id' => $user->id,
        ]);

        $this->assertEquals('Test content', $post->content);
        $this->assertEquals($user->id, $post->user_id);
    }

    public function test_deleting_user_cascades_to_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(2)->create(['user_id' => $user->id]);

        $userId = $user->id;
        $user->delete();

        $this->assertDatabaseMissing('posts', ['user_id' => $userId]);
    }
}
