<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RecipeController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Recipe::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($mealType = $request->query('meal_type')) {
            $query->where('meal_type', $mealType);
        }

        if ($difficulty = $request->query('difficulty')) {
            if (array_key_exists($difficulty, Recipe::DIFFICULTIES)) {
                $query->where('difficulty', $difficulty);
            }
        }

        if ($maxTime = (int) $request->query('max_time', 0)) {
            $query->whereRaw('(prep_time + cook_time) <= ?', [$maxTime]);
        }

        $recipes = $query
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Recipe $r) => $this->cardPayload($r));

        $mealTypes = Recipe::query()
            ->whereNotNull('published_at')
            ->select('meal_type')
            ->distinct()
            ->orderBy('meal_type')
            ->pluck('meal_type')
            ->filter()
            ->values()
            ->all();

        return Inertia::render('Recipes/Index', [
            'recipes' => $recipes,
            'mealTypes' => $mealTypes,
            'difficulties' => Recipe::DIFFICULTIES,
            'filters' => [
                'meal_type' => $mealType ?: null,
                'difficulty' => $difficulty ?: null,
                'max_time' => $maxTime ?: null,
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $recipe = Recipe::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $recipe->increment('views');

        return Inertia::render('Recipes/Show', [
            'recipe' => [
                'id' => $recipe->id,
                'title' => $recipe->title,
                'slug' => $recipe->slug,
                'excerpt' => $recipe->excerpt,
                'cover_image' => $recipe->cover_image,
                'meal_type' => $recipe->meal_type,
                'difficulty' => $recipe->difficulty,
                'difficulty_label' => Recipe::DIFFICULTIES[$recipe->difficulty] ?? ucfirst((string) $recipe->difficulty),
                'prep_time' => (int) $recipe->prep_time,
                'cook_time' => (int) $recipe->cook_time,
                'total_time' => (int) $recipe->prep_time + (int) $recipe->cook_time,
                'servings' => (int) $recipe->servings,
                'ingredients' => $recipe->ingredients ?? [],
                'instructions' => $recipe->instructions ?? [],
                'published_at' => $recipe->published_at?->toDateString(),
                'views' => (int) $recipe->views,
            ],
        ]);
    }

    protected function cardPayload(Recipe $r): array
    {
        return [
            'id' => $r->id,
            'title' => $r->title,
            'slug' => $r->slug,
            'excerpt' => $r->excerpt,
            'cover_image' => $r->cover_image,
            'meal_type' => $r->meal_type,
            'difficulty' => $r->difficulty,
            'difficulty_label' => Recipe::DIFFICULTIES[$r->difficulty] ?? ucfirst((string) $r->difficulty),
            'prep_time' => (int) $r->prep_time,
            'cook_time' => (int) $r->cook_time,
            'total_time' => (int) $r->prep_time + (int) $r->cook_time,
            'servings' => (int) $r->servings,
        ];
    }
}
