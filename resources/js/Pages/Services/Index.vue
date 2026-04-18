<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    services: { type: Array, default: () => [] },
});

const formatNaira = (value) => new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    maximumFractionDigits: 0,
}).format(Number(value ?? 0));

const storageUrl = (path) => (path ? `/storage/${path}` : null);
</script>

<template>
    <Head title="Services — Blue Dine Cuisines" />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-20">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-6">What we offer</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Our services</h1>
                <p class="mt-6 text-cream/80 max-w-2xl">
                    From seated private dinners to weekly meal prep and event catering, every Blue Dine service is built
                    around a small, carefully sourced menu and a single chef responsible for the entire experience.
                </p>
            </div>
        </section>

        <section class="py-20 bg-cream">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div v-if="services.length" class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <article
                        v-for="service in services"
                        :key="service.id"
                        class="bg-white rounded-2xl overflow-hidden border border-primary/5 shadow-sm flex flex-col"
                    >
                        <div class="aspect-[4/3] bg-primary/10 overflow-hidden">
                            <img
                                v-if="service.image"
                                :src="storageUrl(service.image)"
                                :alt="service.title"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="h-full w-full flex items-center justify-center text-primary/30">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-7 flex flex-col flex-1">
                            <h3 class="font-serif text-xl text-primary mb-3">{{ service.title }}</h3>
                            <p class="text-sm text-charcoal/70 leading-relaxed flex-1">{{ service.short_description }}</p>
                            <p class="mt-5 text-sm font-semibold text-primary">
                                From {{ formatNaira(service.base_price) }}
                            </p>
                            <Link
                                :href="route('services.show', service.slug)"
                                class="mt-6 inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-primary text-cream font-semibold text-sm hover:bg-primary/90 transition"
                            >
                                Learn more
                            </Link>
                        </div>
                    </article>
                </div>
                <p v-else class="text-charcoal/60">Services will be published shortly.</p>
            </div>
        </section>
    </PublicLayout>
</template>
