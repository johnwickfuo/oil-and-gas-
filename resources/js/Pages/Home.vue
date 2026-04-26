<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';

const props = defineProps({
    featuredServices: { type: Array, default: () => [] },
    weeklyMenu: { type: Array, default: () => [] },
    featuredTestimonials: { type: Array, default: () => [] },
    latestPosts: { type: Array, default: () => [] },
    settings: { type: Object, default: () => ({}) },
});

const page = usePage();
const site = computed(() => page.props.site || {});

const structuredData = computed(() => [
    {
        '@context': 'https://schema.org',
        '@type': 'LocalBusiness',
        '@id': `${site.value.url}/#business`,
        name: site.value.name || 'Blue Dine Cuisines',
        description: 'Private chef, meal prep, small chops catering and healthy meal delivery in Port Harcourt.',
        url: site.value.url,
        telephone: site.value.phone,
        email: site.value.email,
        image: site.value.og_image,
        priceRange: '₦₦₦',
        address: {
            '@type': 'PostalAddress',
            streetAddress: site.value.address || 'Port Harcourt',
            addressLocality: 'Port Harcourt',
            addressRegion: 'Rivers',
            addressCountry: 'NG',
        },
        geo: {
            '@type': 'GeoCoordinates',
            latitude: 4.8156,
            longitude: 7.0498,
        },
        areaServed: {
            '@type': 'City',
            name: 'Port Harcourt',
        },
        openingHoursSpecification: [
            {
                '@type': 'OpeningHoursSpecification',
                dayOfWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                opens: '09:00',
                closes: '20:00',
            },
        ],
    },
    {
        '@context': 'https://schema.org',
        '@type': 'WebSite',
        url: site.value.url,
        name: site.value.name || 'Blue Dine Cuisines',
        potentialAction: {
            '@type': 'SearchAction',
            target: `${site.value.url}/blog?q={query}`,
            'query-input': 'required name=query',
        },
    },
]);

const nairaFormatter = new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    maximumFractionDigits: 0,
});

const formatNaira = (value) => nairaFormatter.format(Number(value ?? 0));

const storageUrl = (path) => (path ? `/storage/${path}` : null);

const formattedDate = (iso) => {
    if (!iso) return '';
    const d = new Date(iso);
    return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
};

const hasPosts = computed(() => props.latestPosts.length > 0);
</script>

<template>
    <SeoHead
        :title="null"
        description="Blue Dine Cuisines is a private chef and meal prep kitchen in Port Harcourt. Intimate dinners, weekly healthy meal delivery and small chops catering by Chef Eureka Francis."
        :structured-data="structuredData"
    />

    <PublicLayout>
        <section class="relative min-h-[85vh] flex items-center">
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
                        Chef Eureka Francis crafts intimate, seasonal dining experiences,
                        weekly healthy meal delivery in Port Harcourt, and small chops
                        catering that feels unmistakably personal.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        <Link
                            :href="route('booking.create')"
                            class="inline-flex items-center justify-center px-7 py-3.5 rounded-full bg-accent text-charcoal font-semibold shadow-lg hover:bg-accent/90 transition"
                        >
                            Book Now
                        </Link>
                        <Link
                            :href="route('menu')"
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
                <div v-if="featuredServices.length" class="grid gap-6 md:grid-cols-3">
                    <Link
                        v-for="service in featuredServices"
                        :key="service.id"
                        :href="route('services.show', service.slug)"
                        class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition border border-primary/5 flex flex-col"
                    >
                        <div class="aspect-[4/3] bg-primary/10 overflow-hidden">
                            <img loading="lazy" decoding="async"
                                v-if="service.image"
                                :src="storageUrl(service.image)"
                                :alt="service.title"
                                class="h-full w-full object-cover group-hover:scale-105 transition"
                            />
                            <div v-else class="h-full w-full flex items-center justify-center text-primary/30">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-7 flex-1 flex flex-col">
                            <h3 class="font-serif text-xl text-primary mb-3 group-hover:text-accent transition">{{ service.title }}</h3>
                            <p class="text-sm text-charcoal/70 leading-relaxed flex-1">{{ service.short_description }}</p>
                            <p class="mt-6 text-sm font-semibold text-primary">
                                From {{ formatNaira(service.base_price) }}
                            </p>
                        </div>
                    </Link>
                </div>
                <p v-else class="text-charcoal/60">Services coming soon.</p>
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
                        :href="route('menu')"
                        class="text-sm font-semibold text-primary hover:text-accent transition"
                    >
                        View full menu &rarr;
                    </Link>
                </div>
                <div v-if="weeklyMenu.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <article
                        v-for="dish in weeklyMenu"
                        :key="dish.id"
                        class="group rounded-2xl overflow-hidden bg-cream border border-primary/5"
                    >
                        <div class="aspect-[4/3] bg-primary/10 overflow-hidden">
                            <img loading="lazy" decoding="async"
                                v-if="dish.photo"
                                :src="storageUrl(dish.photo)"
                                :alt="dish.name"
                                class="h-full w-full object-cover group-hover:scale-105 transition"
                            />
                            <div v-else class="h-full w-full flex items-center justify-center text-primary/30">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="uppercase tracking-widest text-[10px] text-accent mb-1">{{ dish.category?.replace('_', ' ') }}</p>
                            <h3 class="font-serif text-lg text-primary">{{ dish.name }}</h3>
                            <p class="mt-2 text-sm font-semibold text-primary/80">{{ formatNaira(dish.price) }}</p>
                        </div>
                    </article>
                </div>
                <p v-else class="text-charcoal/60">Menu updates Monday morning.</p>
            </div>
        </section>

        <section v-if="featuredTestimonials.length" class="py-20 bg-primary text-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mb-12">
                    <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Kind Words</p>
                    <h2 class="font-serif text-4xl">What clients say</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <figure
                        v-for="t in featuredTestimonials"
                        :key="t.id"
                        class="bg-cream/5 rounded-2xl p-8 border border-cream/10"
                    >
                        <svg class="h-8 w-8 text-accent mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.17 6A5.17 5.17 0 0 0 2 11.17V18h6.83v-6.83H5.17A2 2 0 0 1 7.17 9V6zm10 0A5.17 5.17 0 0 0 12 11.17V18h6.83v-6.83h-3.66A2 2 0 0 1 17.17 9V6z"/>
                        </svg>
                        <blockquote class="font-serif text-lg leading-relaxed text-cream/90">
                            &ldquo;{{ t.quote }}&rdquo;
                        </blockquote>
                        <figcaption class="mt-6 text-sm text-cream/70">
                            <span class="font-semibold text-cream">{{ t.client_name }}</span>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </section>

        <section v-if="hasPosts" class="py-20 bg-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12 gap-4">
                    <div>
                        <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Journal</p>
                        <h2 class="font-serif text-4xl text-primary">From the Kitchen</h2>
                    </div>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    <article
                        v-for="post in latestPosts"
                        :key="post.id"
                        class="bg-white rounded-2xl overflow-hidden border border-primary/5"
                    >
                        <div class="aspect-[16/9] bg-primary/10">
                            <img loading="lazy" decoding="async"
                                v-if="post.cover_image"
                                :src="storageUrl(post.cover_image)"
                                :alt="post.title"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="p-6">
                            <p class="uppercase tracking-widest text-[10px] text-accent mb-2">{{ post.category }}</p>
                            <h3 class="font-serif text-lg text-primary leading-snug mb-2">{{ post.title }}</h3>
                            <p class="text-sm text-charcoal/70 leading-relaxed">{{ post.excerpt }}</p>
                            <p class="mt-4 text-xs text-charcoal/50">{{ formattedDate(post.published_at) }}</p>
                        </div>
                    </article>
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
