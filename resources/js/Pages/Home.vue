<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const services = [
    {
        title: 'Private Dining',
        description: 'Intimate, chef-curated dinners in your home for two to twenty guests.',
    },
    {
        title: 'Weekly Meal Prep',
        description: 'Wholesome, chef-plated meals delivered weekly across Port Harcourt.',
    },
    {
        title: 'Event Catering',
        description: 'Bespoke menus for engagements, birthdays, and intimate celebrations.',
    },
];

const weeklyMenu = [
    { name: 'Jollof Risotto', tag: 'Signature' },
    { name: 'Pepper Grilled Snapper', tag: 'Coastal' },
    { name: 'Plantain Gnocchi', tag: 'Seasonal' },
    { name: 'Suya Spiced Lamb', tag: 'Chef\u2019s Pick' },
];

const testimonials = [
    {
        quote: 'Eureka turned our anniversary into the most memorable evening we have ever had. Every course was a story.',
        name: 'Amaka & Tobi',
        role: 'Private Dinner, GRA',
    },
    {
        quote: 'The weekly meal prep has genuinely changed how our family eats. Beautifully plated, deeply flavorful.',
        name: 'Dr. Ifeanyi',
        role: 'Meal Prep Client',
    },
];
</script>

<template>
    <Head title="Blue Dine Cuisines - Private Dining, Reimagined" />

    <PublicLayout>
        <section class="relative min-h-screen flex items-center">
            <div class="absolute inset-0 bg-primary"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-charcoal/90 via-primary/80 to-charcoal/90"></div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 text-cream">
                <div class="max-w-3xl">
                    <p class="font-sans uppercase tracking-[0.3em] text-xs text-accent mb-6">
                        Port Harcourt Private Chef
                    </p>
                    <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl leading-tight text-cream">
                        Private Dining, Reimagined
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-cream/80 max-w-2xl">
                        Chef Eureka Francis crafts intimate, seasonal dining experiences
                        and wholesome weekly menus that feel unmistakably personal.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        <Link
                            href="/book"
                            class="inline-flex items-center justify-center px-7 py-3.5 rounded-full bg-accent text-charcoal font-semibold shadow-lg hover:bg-accent/90 transition"
                        >
                            Book Now
                        </Link>
                        <Link
                            href="/menu"
                            class="inline-flex items-center justify-center px-7 py-3.5 rounded-full border border-cream/40 text-cream font-semibold hover:border-accent hover:text-accent transition"
                        >
                            View Menu
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mb-12">
                    <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">What we do</p>
                    <h2 class="font-serif text-4xl text-primary">Services</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <div
                        v-for="service in services"
                        :key="service.title"
                        class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition border border-primary/5"
                    >
                        <div class="h-12 w-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-5">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6" />
                            </svg>
                        </div>
                        <h3 class="font-serif text-xl text-primary mb-3">{{ service.title }}</h3>
                        <p class="text-sm text-charcoal/70 leading-relaxed">{{ service.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12 gap-4">
                    <div>
                        <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">On the table</p>
                        <h2 class="font-serif text-4xl text-primary">This Week&rsquo;s Menu</h2>
                    </div>
                    <Link
                        href="/menu"
                        class="text-sm font-semibold text-primary hover:text-accent transition"
                    >
                        View full menu &rarr;
                    </Link>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <article
                        v-for="dish in weeklyMenu"
                        :key="dish.name"
                        class="group rounded-2xl overflow-hidden bg-cream border border-primary/5"
                    >
                        <div class="aspect-[4/3] bg-primary/10 flex items-center justify-center text-primary/40">
                            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7h16M4 12h16M4 17h16" />
                            </svg>
                        </div>
                        <div class="p-5">
                            <p class="uppercase tracking-widest text-[10px] text-accent mb-1">{{ dish.tag }}</p>
                            <h3 class="font-serif text-lg text-primary">{{ dish.name }}</h3>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="py-20 bg-primary text-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mb-12">
                    <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Kind Words</p>
                    <h2 class="font-serif text-4xl">What clients say</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <figure
                        v-for="t in testimonials"
                        :key="t.name"
                        class="bg-cream/5 rounded-2xl p-8 border border-cream/10"
                    >
                        <svg class="h-8 w-8 text-accent mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.17 6A5.17 5.17 0 0 0 2 11.17V18h6.83v-6.83H5.17A2 2 0 0 1 7.17 9V6zm10 0A5.17 5.17 0 0 0 12 11.17V18h6.83v-6.83h-3.66A2 2 0 0 1 17.17 9V6z"/>
                        </svg>
                        <blockquote class="font-serif text-xl leading-relaxed text-cream/90">
                            &ldquo;{{ t.quote }}&rdquo;
                        </blockquote>
                        <figcaption class="mt-6 text-sm text-cream/70">
                            <span class="font-semibold text-cream">{{ t.name }}</span>
                            <span class="mx-2">&middot;</span>
                            <span>{{ t.role }}</span>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </section>

        <section class="py-16 bg-accent">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="font-serif text-3xl sm:text-4xl text-charcoal">
                    Seasonal menus, straight to your inbox
                </h2>
                <p class="mt-3 text-charcoal/80">
                    Be the first to know about new weekly menus and upcoming dining events.
                </p>
                <form class="mt-8 flex flex-col sm:flex-row gap-3 max-w-md mx-auto" @submit.prevent>
                    <input
                        type="email"
                        placeholder="you@example.com"
                        class="flex-1 rounded-full border-0 bg-cream px-5 py-3 text-sm text-charcoal placeholder:text-charcoal/50 focus:ring-2 focus:ring-primary"
                    />
                    <button
                        type="submit"
                        class="rounded-full bg-primary text-cream font-semibold text-sm px-6 py-3 hover:bg-primary/90 transition"
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </section>
    </PublicLayout>
</template>
