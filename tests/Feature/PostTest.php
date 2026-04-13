<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Home page
    // -------------------------------------------------------------------------

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_home_page_displays_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->create(['content' => 'Hello world', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Hello world');
    }

    // -------------------------------------------------------------------------
    // Create post
    // -------------------------------------------------------------------------

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/create-post', [
            'content' => 'My first post',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('posts', [
            'content' => 'My first post',
            'user_id' => $user->id,
        ]);
    }

    public function test_create_post_requires_content(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/create-post', [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_create_post_content_max_255_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/create-post', [
            'content' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors('content');
    }

    // -------------------------------------------------------------------------
    // Edit post form
    // -------------------------------------------------------------------------

    public function test_owner_can_view_edit_form(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/post/{$post->id}/edit");

        $response->assertStatus(200);
    }

    public function test_non_owner_cannot_view_edit_form(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->get("/post/{$post->id}/edit");

        $response->assertStatus(403);
    }

    // -------------------------------------------------------------------------
    // Update post
    // -------------------------------------------------------------------------

    public function test_owner_can_update_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'content' => 'Old content']);

        $response = $this->actingAs($user)->put("/post/{$post->id}", [
            'content' => 'Updated content',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'content' => 'Updated content']);
    }

    public function test_non_owner_cannot_update_post(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post  = Post::factory()->create(['user_id' => $owner->id, 'content' => 'Original']);

        $response = $this->actingAs($other)->put("/post/{$post->id}", [
            'content' => 'Hacked',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'content' => 'Original']);
    }

    public function test_update_post_requires_content(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/post/{$post->id}", [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_update_post_content_max_255_characters(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/post/{$post->id}", [
            'content' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors('content');
    }

    // -------------------------------------------------------------------------
    // Delete post
    // -------------------------------------------------------------------------

    public function test_owner_can_delete_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/post/{$post->id}");

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_non_owner_cannot_delete_post(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->delete("/post/{$post->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
