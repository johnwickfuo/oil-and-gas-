<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    recipes: { type: Object, required: true },
    mealTypes: { type: Array, default: () => [] },
    difficulties: { type: Object, default: () => ({}) },
    filters: { type: Object, default: () => ({}) },
});

const mealType = ref(props.filters.meal_type || '');
const difficulty = ref(props.filters.difficulty || '');
const maxTime = ref(props.filters.max_time || '');

function apply() {
    router.get(route('recipes.index'), {
        meal_type: mealType.value || null,
        difficulty: difficulty.value || null,
        max_time: maxTime.value || null,
    }, { preserveScroll: true, preserveState: true, replace: true });
}

function reset() {
    mealType.value = '';
    difficulty.value = '';
    maxTime.value = '';
    apply();
}

const storageUrl = (path) => (path ? `/storage/${path}` : null);
</script>

<template>
    <Head title="Recipes — Blue Dine Cuisines" />
    <PublicLayout>
        <section class="bg-primary text-cream py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Recipes</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Cook like we do at home</h1>
                <p class="mt-4 text-cream/80 max-w-2xl">Tested recipes from our kitchen — with print-friendly versions so you can take them to the stove.</p>
            </div>
        </section>

        <section class="bg-cream border-y border-primary/10 py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid gap-4 md:grid-cols-4 items-end">
                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Meal</label>
                    <select v-model="mealType" @change="apply"
                            class="w-full rounded-full border border-primary/20 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary">
                        <option value="">All meals</option>
                        <option v-for="m in mealTypes" :key="m" :value="m">{{ m.replace('_', ' ') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Difficulty</label>
                    <select v-model="difficulty" @change="apply"
                            class="w-full rounded-full border border-primary/20 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary">
                        <option value="">All levels</option>
                        <option v-for="(label, key) in difficulties" :key="key" :value="key">{{ label }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Max time (min)</label>
                    <input v-model="maxTime" type="number" min="5" step="5" @change="apply"
                           class="w-full rounded-full border border-primary/20 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary"
                           placeholder="e.g. 45" />
                </div>
                <div>
                    <button type="button" @click="reset"
                            class="w-full rounded-full border border-primary/30 text-primary font-semibold text-sm px-5 py-2.5 hover:bg-primary hover:text-cream transition">
                        Reset filters
                    </button>
                </div>
            </div>
        </section>

        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-if="recipes.data.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <article v-for="recipe in recipes.data" :key="recipe.id"
                             class="group bg-white rounded-2xl overflow-hidden border border-primary/5 hover:shadow-md transition">
                        <Link :href="route('recipes.show', recipe.slug)" class="block">
                            <div class="aspect-[4/3] bg-primary/10 overflow-hidden">
                                <img v-if="recipe.cover_image" :src="storageUrl(recipe.cover_image)" :alt="recipe.title"
                                     class="h-full w-full object-cover group-hover:scale-105 transition" />
                            </div>
                            <div class="p-6">
                                <p class="uppercase tracking-widest text-[10px] text-accent mb-2">{{ (recipe.meal_type || '').replace('_', ' ') }}</p>
                                <h3 class="font-serif text-lg text-primary group-hover:text-accent transition">{{ recipe.title }}</h3>
                                <p class="mt-2 text-sm text-charcoal/70 line-clamp-2">{{ recipe.excerpt }}</p>
                                <dl class="mt-4 grid grid-cols-3 gap-2 text-xs text-charcoal/70">
                                    <div>
                                        <dt class="uppercase tracking-widest text-[9px] text-accent">Time</dt>
                                        <dd>{{ recipe.total_time }} min</dd>
                                    </div>
                                    <div>
                                        <dt class="uppercase tracking-widest text-[9px] text-accent">Serves</dt>
                                        <dd>{{ recipe.servings }}</dd>
                                    </div>
                                    <div>
                                        <dt class="uppercase tracking-widest text-[9px] text-accent">Level</dt>
                                        <dd>{{ recipe.difficulty_label }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </Link>
                    </article>
                </div>
                <p v-else class="text-charcoal/60">No recipes match those filters.</p>

                <nav v-if="recipes.links?.length > 3" class="mt-12 flex flex-wrap justify-center gap-2">
                    <Link v-for="link in recipes.links" :key="link.label"
                          :href="link.url || '#'"
                          v-html="link.label"
                          preserve-scroll
                          class="px-4 py-2 text-sm rounded-full border transition"
                          :class="link.active ? 'bg-primary text-cream border-primary' : (link.url ? 'border-primary/20 text-charcoal hover:border-primary' : 'border-charcoal/10 text-charcoal/30 pointer-events-none')"
                    />
                </nav>
            </div>
        </section>
    </PublicLayout>
</template>
