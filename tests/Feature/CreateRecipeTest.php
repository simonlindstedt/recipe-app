<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateRecipeTest extends TestCase
{
    use RefreshDatabase;
    public function test_view_create_recipe_form()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertSeeText("Title");
    }

    public function test_hide_create_recipe_form_if_not_authenticaded()
    {
        $response = $this->get('/');
        $this->assertGuest();
        $response->assertDontSeeText('title');
    }

    public function test_add_new_recipe()
    {
        $user = User::factory()->create();
        $title = "Test recipe";
        $content = "Test content";

        $this->actingAs($user)->post('create-recipe', [
            'title' => $title,
            'content' => $content,
        ]);

        $this->assertDatabaseHas('recipes', [
            'id' => 1,
            'title' => $title,
            'content' => $content,
        ]);
    }

    public function test_redirect_to_index_after_post()
    {
        $user = User::factory()->create();
        $response = $this->followingRedirects()->actingAs($user)->post('create-recipe', [
            'title' => 'Test title',
            'content' => 'Test content',
        ]);
        $response->assertViewIs('index');
    }
}