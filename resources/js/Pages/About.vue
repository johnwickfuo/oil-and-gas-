<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';

defineProps({
    settings: { type: Object, default: () => ({}) },
});

const philosophy = [
    {
        title: 'Market-first menus',
        body: 'Every week starts at Mile 1 Market. What is in season shapes what lands on your table — never the other way around.',
    },
    {
        title: 'Cooked for you, not a crowd',
        body: 'Whether it is a dinner for two or a prep order for a family of six, each dish is plated and portioned as if it were our own table.',
    },
    {
        title: 'Hospitality that travels',
        body: 'Chef Eureka arrives with every tool and ingredient, and leaves your kitchen cleaner than we found it. You host, we handle the rest.',
    },
];

const credentials = [
    'Le Cordon Bleu trained, London',
    '10+ years of private dining across West Africa',
    'Port Harcourt Hospitality Guild member',
    'Food safety & HACCP certified kitchen',
];
</script>

<template>
    <SeoHead
        title="About Chef Eureka"
        description="Meet Chef Eureka Francis — the private chef behind Blue Dine Cuisines, Port Harcourt's home for intimate dining, meal prep and small chops catering."
        :structured-data="{
            '@context': 'https://schema.org',
            '@type': 'AboutPage',
            name: 'About Blue Dine Cuisines',
            url: $page.props.site?.url + '/about',
        }"
    />

    <PublicLayout>
        <section class="relative bg-primary text-cream">
            <div class="absolute inset-0 bg-gradient-to-br from-charcoal/80 via-primary/80 to-charcoal/80"></div>
            <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-24 sm:py-28">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-6">Our story</p>
                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl leading-tight">
                    Meet Chef Eureka Francis
                </h1>
                <p class="mt-6 text-lg text-cream/80 max-w-3xl">
                    Blue Dine Cuisines is the private chef studio of Eureka Francis — a Port Harcourt kitchen dedicated
                    to intimate dining experiences, thoughtful weekly meal prep, and celebrations that feel deeply
                    personal.
                </p>
            </div>
        </section>

        <section class="py-20 bg-cream">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-start">
                <div>
                    <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">The kitchen</p>
                    <h2 class="font-serif text-3xl sm:text-4xl text-primary">A practice built around the guest</h2>
                    <div class="mt-6 space-y-5 text-charcoal/80 leading-relaxed">
                        <p>
                            Blue Dine began in 2019 with a weekly dinner table for six. Five years later the kitchen still
                            runs at that scale — small enough to know every guest's palate, rigorous enough to hold a
                            professional standard across private dinners, weekly meal prep and curated small chops trays.
                        </p>
                        <p>
                            Eureka cooks from a West African larder with a classical European hand, drawing on sourcing
                            relationships across Mile 1, Abonnema Wharf and the Port Harcourt farms that supply our
                            produce each week.
                        </p>
                    </div>
                </div>
                <aside class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm">
                    <h3 class="font-serif text-xl text-primary mb-5">Credentials</h3>
                    <ul class="space-y-3 text-sm text-charcoal/80">
                        <li v-for="c in credentials" :key="c" class="flex gap-3">
                            <svg class="h-5 w-5 text-accent flex-none mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ c }}</span>
                        </li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="py-20 bg-white">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mb-12">
                    <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Philosophy</p>
                    <h2 class="font-serif text-3xl sm:text-4xl text-primary">How we think about food</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <article
                        v-for="item in philosophy"
                        :key="item.title"
                        class="bg-cream rounded-2xl p-8 border border-primary/5"
                    >
                        <h3 class="font-serif text-xl text-primary mb-3">{{ item.title }}</h3>
                        <p class="text-sm text-charcoal/70 leading-relaxed">{{ item.body }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="py-16 bg-accent">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="font-serif text-3xl sm:text-4xl text-charcoal">
                    Ready to plan your next meal with us?
                </h2>
                <p class="mt-3 text-charcoal/80">
                    Browse the weekly menu, explore our services, or book a discovery call.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <Link
                        :href="route('services.index')"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-primary text-cream font-semibold text-sm hover:bg-primary/90 transition"
                    >
                        View services
                    </Link>
                    <Link
                        :href="route('contact')"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-charcoal/30 text-charcoal font-semibold text-sm hover:border-primary hover:text-primary transition"
                    >
                        Say hello
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
