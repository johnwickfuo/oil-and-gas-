<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    recipe: { type: Object, required: true },
});

const storageUrl = (path) => (path ? `/storage/${path}` : null);

const pageUrl = computed(() => (typeof window !== 'undefined' ? window.location.href : ''));
const shareTitle = computed(() => `${props.recipe.title} — Blue Dine Cuisines`);

const twitterHref = computed(() => `https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl.value)}&text=${encodeURIComponent(shareTitle.value)}`);
const facebookHref = computed(() => `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl.value)}`);
const whatsappHref = computed(() => `https://wa.me/?text=${encodeURIComponent(shareTitle.value + ' ' + pageUrl.value)}`);

const copied = ref(false);
async function copyLink() {
    try {
        await navigator.clipboard.writeText(pageUrl.value);
        copied.value = true;
        setTimeout(() => (copied.value = false), 2000);
    } catch (e) {
        // noop
    }
}

function printRecipe() {
    if (typeof window !== 'undefined') window.print();
}
</script>

<template>
    <Head :title="`${recipe.title} — Blue Dine Recipes`" />
    <PublicLayout>
        <article class="recipe-article">
            <section class="relative bg-primary text-cream">
                <div class="absolute inset-0 opacity-30 no-print">
                    <img v-if="recipe.cover_image" :src="storageUrl(recipe.cover_image)" :alt="recipe.title"
                         class="h-full w-full object-cover" />
                </div>
                <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-20">
                    <Link :href="route('recipes.index')" class="text-xs uppercase tracking-[0.3em] text-accent hover:text-accent/80 no-print">
                        &larr; All recipes
                    </Link>
                    <p class="mt-6 uppercase tracking-[0.3em] text-xs text-accent">{{ (recipe.meal_type || '').replace('_', ' ') }}</p>
                    <h1 class="mt-4 font-serif text-4xl sm:text-5xl leading-tight">{{ recipe.title }}</h1>
                    <p v-if="recipe.excerpt" class="mt-4 text-cream/80 max-w-2xl">{{ recipe.excerpt }}</p>
                </div>
            </section>

            <section class="py-14 bg-cream">
                <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-white rounded-2xl p-6 border border-primary/5 shadow-sm">
                        <div>
                            <dt class="uppercase tracking-widest text-[10px] text-accent">Prep</dt>
                            <dd class="font-serif text-2xl text-primary">{{ recipe.prep_time }}<span class="text-sm font-sans text-charcoal/60"> min</span></dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-widest text-[10px] text-accent">Cook</dt>
                            <dd class="font-serif text-2xl text-primary">{{ recipe.cook_time }}<span class="text-sm font-sans text-charcoal/60"> min</span></dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-widest text-[10px] text-accent">Serves</dt>
                            <dd class="font-serif text-2xl text-primary">{{ recipe.servings }}</dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-widest text-[10px] text-accent">Level</dt>
                            <dd class="font-serif text-2xl text-primary">{{ recipe.difficulty_label }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex flex-wrap gap-3 no-print">
                        <button type="button" @click="printRecipe"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary text-cream text-sm font-semibold hover:bg-primary/90 transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2m2 4h6a2 2 0 0 0 2-2v-4H7v4a2 2 0 0 0 2 2zM7 9V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4" />
                            </svg>
                            Print recipe
                        </button>
                        <a :href="twitterHref" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 text-sm text-charcoal hover:border-primary hover:text-primary transition">
                            Twitter
                        </a>
                        <a :href="facebookHref" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 text-sm text-charcoal hover:border-primary hover:text-primary transition">
                            Facebook
                        </a>
                        <a :href="whatsappHref" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 text-sm text-charcoal hover:border-primary hover:text-primary transition">
                            WhatsApp
                        </a>
                        <button type="button" @click="copyLink"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 text-sm text-charcoal hover:border-primary hover:text-primary transition">
                            {{ copied ? 'Copied!' : 'Copy link' }}
                        </button>
                    </div>

                    <div class="mt-10 grid gap-10 md:grid-cols-5">
                        <div class="md:col-span-2 bg-white rounded-2xl border border-primary/5 p-6 sm:p-8">
                            <h2 class="font-serif text-2xl text-primary mb-5">Ingredients</h2>
                            <ul class="space-y-3 text-sm text-charcoal/85">
                                <li v-for="(ing, idx) in recipe.ingredients" :key="idx" class="flex gap-3 items-start">
                                    <span class="flex-none h-2 w-2 rounded-full bg-accent mt-2"></span>
                                    <span>
                                        <span class="font-semibold">{{ ing.quantity }}</span>
                                        <span v-if="ing.quantity"> · </span>
                                        <span>{{ ing.item }}</span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="md:col-span-3 bg-white rounded-2xl border border-primary/5 p-6 sm:p-8">
                            <h2 class="font-serif text-2xl text-primary mb-5">Instructions</h2>
                            <ol class="space-y-5 text-charcoal/85">
                                <li v-for="(step, idx) in recipe.instructions" :key="idx" class="flex gap-4">
                                    <span class="flex-none h-8 w-8 rounded-full bg-accent text-charcoal font-semibold text-sm flex items-center justify-center">
                                        {{ idx + 1 }}
                                    </span>
                                    <p class="leading-relaxed pt-1">{{ step }}</p>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
        </article>
    </PublicLayout>
</template>

<style>
@media print {
    header, footer, .no-print, nav[aria-label="Menu"], aside[class*="w-72"] {
        display: none !important;
    }
    body {
        background: white !important;
        color: black !important;
    }
    .recipe-article section {
        padding: 0 !important;
        background: white !important;
        color: black !important;
    }
    .recipe-article h1, .recipe-article h2 {
        color: black !important;
    }
    .recipe-article .bg-primary,
    .recipe-article .bg-white,
    .recipe-article .bg-cream {
        background: white !important;
    }
    .recipe-article .text-cream,
    .recipe-article .text-primary,
    .recipe-article .text-charcoal\/85,
    .recipe-article .text-charcoal\/70 {
        color: black !important;
    }
    .recipe-article .text-accent {
        color: #555 !important;
    }
    .recipe-article [class*="rounded-"] {
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    a[href]:after {
        content: none !important;
    }
}
</style>
