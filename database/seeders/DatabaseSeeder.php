<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\MenuItem;
use App\Models\Recipe;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use App\Support\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => '[email protected]'],
            [
                'name' => 'Eureka Francis',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        $this->seedSettings();
        $this->seedServices();
        $this->seedMenuItems();
        $this->seedTestimonials();
        $this->seedBlogPosts($admin->id);
        $this->seedRecipes();
        $this->seedResources();
    }

    private function seedSettings(): void
    {
        $defaults = [
            'contact_email' => 'hello@bluedinecuisines.com',
            'contact_phone' => '+234 803 000 0000',
            'whatsapp_number' => '2348030000000',
            'instagram_url' => 'https://instagram.com/bluedinecuisines',
            'facebook_url' => 'https://facebook.com/bluedinecuisines',
            'twitter_url' => '',
            'deposit_percentage' => '30',
            'business_address' => 'Port Harcourt, Rivers State, Nigeria',
            'service_area' => 'Port Harcourt and environs',
        ];

        foreach ($defaults as $key => $value) {
            Settings::set($key, $value);
        }
    }

    private function seedServices(): void
    {
        $services = [
            [
                'title' => 'Private Chef Dinner',
                'short_description' => 'Chef-curated dining experiences in your home, from intimate two-seaters to festive tables of twenty.',
                'long_description' => '<p>A fully bespoke dinner designed around your guests, allergies and occasion. Chef Eureka arrives with every ingredient, plates each course at your table, and leaves your kitchen cleaner than we found it.</p><p>Menus draw from coastal Nigerian produce with Mediterranean and continental accents, and every booking includes a discovery call to fine-tune the courses.</p>',
                'base_price' => 250000.00,
            ],
            [
                'title' => 'Weekly Meal Prep',
                'short_description' => 'Wholesome, chef-plated meals delivered across Port Harcourt on a weekly rotation.',
                'long_description' => '<p>A five-day meal plan delivered every Sunday evening, built from the week\'s market-fresh produce. Choose from signature proteins, sides and swallows, with options for low-carb, pescatarian, and family-size portions.</p><p>Packaging is oven-safe and reusable, and every order includes reheating instructions and a simple pairing guide.</p>',
                'base_price' => 85000.00,
            ],
            [
                'title' => 'Small Chops Catering',
                'short_description' => 'Gold-standard small chops trays for engagements, birthdays and office events.',
                'long_description' => '<p>Platters of puff puff, samosa, spring rolls, peppered gizzards, suya skewers and seasonal dips, arranged on presentation boards sized for 10, 25 or 50 guests.</p><p>Delivery across Port Harcourt with hot-hold packaging so everything arrives crisp. Vegetarian and pepper-free options available.</p>',
                'base_price' => 120000.00,
            ],
        ];

        foreach ($services as $i => $data) {
            Service::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge($data, [
                    'slug' => Str::slug($data['title']),
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]),
            );
        }
    }

    private function seedMenuItems(): void
    {
        $weekOf = Carbon::now()->startOfWeek(Carbon::MONDAY);

        $items = [
            ['name' => 'Suya Spiced Lamb', 'category' => 'protein', 'price' => 12000, 'description' => 'Slow-roasted lamb rubbed with our house suya blend.'],
            ['name' => 'Pepper Grilled Snapper', 'category' => 'protein', 'price' => 15000, 'description' => 'Whole snapper, scored and grilled with atarodo and citrus.'],
            ['name' => 'Jollof Risotto', 'category' => 'side', 'price' => 6500, 'description' => 'Arborio rice cooked down in smoky jollof stock.'],
            ['name' => 'Coconut Rice with Prawns', 'category' => 'side', 'price' => 7500, 'description' => 'Fragrant coconut rice, prawns, and charred bell peppers.'],
            ['name' => 'Amala with Gbegiri', 'category' => 'swallow', 'price' => 5500, 'description' => 'Silky amala paired with creamy gbegiri and ewedu.'],
            ['name' => 'Hibiscus Sorbet', 'category' => 'dessert', 'price' => 4500, 'description' => 'Bright zobo-lime sorbet with crystallised ginger.'],
            ['name' => 'Chapman Fizz', 'category' => 'drink', 'price' => 3500, 'description' => 'Classic chapman with house-made grenadine.'],
            ['name' => 'Signature Small Chops Tray', 'category' => 'small_chops', 'price' => 18000, 'description' => 'Puff puff, samosa, spring rolls, peppered gizzards, suya skewers.'],
        ];

        foreach ($items as $i => $item) {
            MenuItem::updateOrCreate(
                ['name' => $item['name'], 'week_of' => $weekOf->toDateString()],
                array_merge($item, [
                    'week_of' => $weekOf->toDateString(),
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]),
            );
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            ['client_name' => 'Amaka & Tobi', 'rating' => 5, 'is_featured' => true,
                'quote' => 'Eureka turned our anniversary into the most memorable evening we have ever had. Every course was a story.'],
            ['client_name' => 'Dr. Ifeanyi', 'rating' => 5, 'is_featured' => true,
                'quote' => 'The weekly meal prep has genuinely changed how our family eats. Beautifully plated, deeply flavorful.'],
            ['client_name' => 'Ngozi O.', 'rating' => 5, 'is_featured' => false,
                'quote' => 'The small chops trays at my son\'s naming ceremony were the talk of the room. Hot, crisp and impeccably presented.'],
            ['client_name' => 'The Okonkwos', 'rating' => 4, 'is_featured' => false,
                'quote' => 'Warm, professional and genuinely skilled. We felt completely looked after from the tasting to the clean-up.'],
            ['client_name' => 'Funmi A.', 'rating' => 5, 'is_featured' => true,
                'quote' => 'I\'ve booked chefs from Lagos to Dubai and Eureka is easily in the top three. The coastal menu blew me away.'],
        ];

        foreach ($testimonials as $i => $t) {
            Testimonial::updateOrCreate(
                ['client_name' => $t['client_name'], 'quote' => $t['quote']],
                array_merge($t, ['sort_order' => $i + 1]),
            );
        }
    }

    private function seedBlogPosts(int $authorId): void
    {
        $posts = [
            [
                'title' => 'Sourcing in Port Harcourt: Where Our Menus Really Start',
                'category' => 'Behind The Scenes',
                'excerpt' => 'From Mile 1 Market to the jetties at Abonnema Wharf, a walk-through of where Blue Dine\'s weekly produce comes from.',
                'body' => '<p>Every Monday before the dinner week begins, our kitchen starts at the Mile 1 Market. The snapper we serve on Thursday is usually on ice by Tuesday morning; the tomatoes in the jollof risotto were in a stall the day before.</p><p>This post walks you through our produce route, the vendors we have loved for years, and how a Port Harcourt pantry shapes what ends up on your table.</p>',
                'tags' => ['port harcourt', 'sourcing', 'behind the scenes'],
            ],
            [
                'title' => 'Plating for a Private Dinner of Eight',
                'category' => 'Craft',
                'excerpt' => 'A working chef\'s notes on how we design, prep and plate a four-course private dinner for a household of eight.',
                'body' => '<p>Small dinners look simple from the outside, but an eight-person private menu has a rhythm. The first hour is set-up, the next hour is ensuring every protein reaches temperature at exactly the same moment.</p><p>We walk through a recent Saturday: prep list, equipment, service choreography, and the final dessert plating sequence.</p>',
                'tags' => ['technique', 'private dining'],
            ],
            [
                'title' => 'The Case for Jollof Risotto',
                'category' => 'Menu Notes',
                'excerpt' => 'Why we cross-pollinated the two most iconic rice dishes in our repertoire, and how to taste the result properly.',
                'body' => '<p>Jollof is Nigerian comfort food; risotto is an Italian technique. Our jollof risotto uses locally grown ofada as the base and a smoky jollof stock as the cooking liquid.</p><p>In this post we break down the build, the tasting order we recommend, and the wine pairings that hold up without overpowering the atarodo heat.</p>',
                'tags' => ['menu', 'jollof', 'risotto'],
            ],
        ];

        foreach ($posts as $p) {
            BlogPost::updateOrCreate(
                ['slug' => Str::slug($p['title'])],
                [
                    'title' => $p['title'],
                    'slug' => Str::slug($p['title']),
                    'excerpt' => $p['excerpt'],
                    'body' => $p['body'],
                    'author_id' => $authorId,
                    'category' => $p['category'],
                    'tags' => $p['tags'],
                    'published_at' => now()->subDays(rand(1, 30)),
                    'meta_title' => $p['title'].' | Blue Dine Cuisines',
                    'meta_description' => $p['excerpt'],
                ],
            );
        }
    }

    private function seedRecipes(): void
    {
        $recipes = [
            [
                'title' => 'Party Jollof Rice',
                'excerpt' => 'Our smoky party-style jollof with the deep tomato base that makes guests come back for seconds.',
                'prep_time' => 20, 'cook_time' => 60, 'servings' => 6,
                'difficulty' => 'medium', 'meal_type' => 'Main',
                'ingredients' => [
                    ['item' => 'Long-grain parboiled rice', 'quantity' => '500g'],
                    ['item' => 'Plum tomatoes', 'quantity' => '6'],
                    ['item' => 'Red bell peppers', 'quantity' => '4'],
                    ['item' => 'Atarodo (scotch bonnet)', 'quantity' => '2'],
                    ['item' => 'Onion', 'quantity' => '1 large'],
                    ['item' => 'Vegetable stock', 'quantity' => '750ml'],
                    ['item' => 'Curry powder', 'quantity' => '1 tbsp'],
                    ['item' => 'Bay leaves', 'quantity' => '2'],
                ],
                'instructions' => [
                    'Blend the tomatoes, peppers, atarodo and half the onion until smooth, then reduce in a heavy pot for 20 minutes.',
                    'Saute the remaining onion, add curry and bay leaves, then pour in the reduced pepper mixture and simmer.',
                    'Add the rinsed rice and stock, cover tightly, and cook on a low flame for 35-40 minutes until liquid is absorbed.',
                    'Lift the lid for the last five minutes on a higher flame to develop the signature smoky bottom.',
                ],
            ],
            [
                'title' => 'Egusi Soup',
                'excerpt' => 'A rich, protein-packed egusi soup with beef, stockfish and bitterleaf.',
                'prep_time' => 30, 'cook_time' => 75, 'servings' => 8,
                'difficulty' => 'medium', 'meal_type' => 'Soup',
                'ingredients' => [
                    ['item' => 'Ground egusi (melon seeds)', 'quantity' => '300g'],
                    ['item' => 'Assorted beef & tripe', 'quantity' => '800g'],
                    ['item' => 'Stockfish', 'quantity' => '100g'],
                    ['item' => 'Palm oil', 'quantity' => '150ml'],
                    ['item' => 'Ground crayfish', 'quantity' => '2 tbsp'],
                    ['item' => 'Bitterleaf (washed)', 'quantity' => '2 handfuls'],
                    ['item' => 'Atarodo', 'quantity' => '3'],
                    ['item' => 'Seasoning & salt', 'quantity' => 'to taste'],
                ],
                'instructions' => [
                    'Season and boil the beef, tripe and stockfish until tender; reserve the stock.',
                    'Heat palm oil, add ground egusi and toast until it forms small lumps and smells nutty.',
                    'Pour in the reserved stock, add crayfish, atarodo and seasoning; simmer for 20 minutes.',
                    'Stir in the meats and bitterleaf, simmer a further 10 minutes, and serve with pounded yam or eba.',
                ],
            ],
            [
                'title' => 'Nigerian Meat Pie',
                'excerpt' => 'Flaky, buttery meat pies with a soft, well-seasoned minced beef and potato filling.',
                'prep_time' => 40, 'cook_time' => 30, 'servings' => 12,
                'difficulty' => 'easy', 'meal_type' => 'Snack',
                'ingredients' => [
                    ['item' => 'Plain flour', 'quantity' => '500g'],
                    ['item' => 'Unsalted butter, cold', 'quantity' => '250g'],
                    ['item' => 'Baking powder', 'quantity' => '1 tsp'],
                    ['item' => 'Ice water', 'quantity' => '120ml'],
                    ['item' => 'Minced beef', 'quantity' => '400g'],
                    ['item' => 'Potato, diced', 'quantity' => '1 large'],
                    ['item' => 'Carrot, diced', 'quantity' => '1'],
                    ['item' => 'Onion, chopped', 'quantity' => '1'],
                    ['item' => 'Seasoning cube, thyme, curry', 'quantity' => 'to taste'],
                    ['item' => 'Egg wash', 'quantity' => '1 egg'],
                ],
                'instructions' => [
                    'Rub the cold butter into the flour and baking powder until sandy, then bring together with ice water. Rest for 30 minutes.',
                    'Saute the onion, brown the mince, then add carrot, potato, seasoning and a splash of water. Cook until the potato is just tender and the filling is moist but not wet.',
                    'Roll the pastry to 3mm, cut discs, fill, crimp and brush with egg wash.',
                    'Bake at 190C for 25-30 minutes until deeply golden.',
                ],
            ],
        ];

        foreach ($recipes as $r) {
            Recipe::updateOrCreate(
                ['slug' => Str::slug($r['title'])],
                array_merge($r, [
                    'slug' => Str::slug($r['title']),
                    'published_at' => now()->subDays(rand(1, 60)),
                ]),
            );
        }
    }

    private function seedResources(): void
    {
        $items = [
            [
                'title' => 'Blue Dine Weekly Menu Planner',
                'description' => 'A printable planner to map your household menu against our weekly rotation.',
                'file' => 'resources/weekly-menu-planner.pdf',
            ],
            [
                'title' => 'Signature Jollof Rice Recipe Card',
                'description' => 'The printable version of our party jollof method, sized for a household of six.',
                'file' => 'resources/jollof-recipe-card.pdf',
            ],
        ];

        foreach ($items as $item) {
            Resource::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                array_merge($item, [
                    'slug' => Str::slug($item['title']),
                    'is_active' => true,
                ]),
            );
        }
    }
}
