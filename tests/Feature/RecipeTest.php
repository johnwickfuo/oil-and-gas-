<?php

namespace Tests\Feature;

use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    protected function makeRecipe(array $overrides = []): Recipe
    {
        return Recipe::query()->create(array_merge([
            'title' => 'Jollof Rice',
            'slug' => 'jollof-rice',
            'excerpt' => 'The classic party favourite.',
            'ingredients' => [
                ['item' => 'Long grain rice', 'quantity' => '500g'],
                ['item' => 'Tomato', 'quantity' => '4 large'],
            ],
            'instructions' => ['Wash rice.', 'Blend tomatoes.', 'Simmer.'],
            'cover_image' => null,
            'prep_time' => 20,
            'cook_time' => 40,
            'servings' => 6,
            'difficulty' => 'medium',
            'meal_type' => 'lunch',
            'published_at' => now()->subDay(),
            'views' => 0,
        ], $overrides));
    }

    public function test_show_increments_views(): void
    {
        $recipe = $this->makeRecipe();

        $this->get("/recipes/{$recipe->slug}")->assertOk();
        $this->get("/recipes/{$recipe->slug}")->assertOk();

        $this->assertSame(2, (int) $recipe->fresh()->views);
    }

    public function test_index_filters_by_difficulty_and_max_time(): void
    {
        $this->makeRecipe([
            'slug' => 'quick-breakfast',
            'title' => 'Quick Breakfast',
            'difficulty' => 'easy',
            'prep_time' => 5,
            'cook_time' => 10,
            'meal_type' => 'breakfast',
        ]);
        $this->makeRecipe([
            'slug' => 'slow-stew',
            'title' => 'Slow Stew',
            'difficulty' => 'hard',
            'prep_time' => 30,
            'cook_time' => 90,
            'meal_type' => 'dinner',
        ]);

        $response = $this->get('/recipes?difficulty=easy&max_time=20');

        $response->assertOk();
        $response->assertInertia(fn ($p) => $p
            ->component('Recipes/Index')
            ->has('recipes.data', 1)
            ->where('recipes.data.0.slug', 'quick-breakfast')
        );
    }

    public function test_show_404_for_unpublished_recipe(): void
    {
        $recipe = $this->makeRecipe(['published_at' => null]);

        $this->get("/recipes/{$recipe->slug}")->assertNotFound();
    }
}
