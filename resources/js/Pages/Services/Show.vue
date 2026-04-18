<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import WhatsAppButton from '@/Components/WhatsAppButton.vue';

const props = defineProps({
    service: { type: Object, required: true },
    settings: { type: Object, default: () => ({}) },
});

const formatNaira = (value) => new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    maximumFractionDigits: 0,
}).format(Number(value ?? 0));

const imageSrc = computed(() => (props.service.image ? `/storage/${props.service.image}` : null));

const bookHref = computed(() => `/book?service=${encodeURIComponent(props.service.slug)}`);

const whatsappMessage = computed(
    () => `Hi Blue Dine, I would like to enquire about the "${props.service.title}" service.`,
);
</script>

<template>
    <Head :title="`${service.title} — Blue Dine Cuisines`" />

    <PublicLayout>
        <section class="relative bg-primary text-cream">
            <div class="absolute inset-0 bg-gradient-to-br from-charcoal/80 via-primary/80 to-charcoal/80"></div>
            <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-20 grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <Link :href="route('services.index')" class="text-xs uppercase tracking-[0.3em] text-accent hover:text-accent/80">
                        &larr; All services
                    </Link>
                    <h1 class="mt-6 font-serif text-4xl sm:text-5xl">{{ service.title }}</h1>
                    <p class="mt-6 text-cream/80 text-lg max-w-xl">{{ service.short_description }}</p>
                    <p class="mt-8 text-sm uppercase tracking-[0.2em] text-accent">Starting from</p>
                    <p class="font-serif text-3xl mt-1">{{ formatNaira(service.base_price) }}</p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <Link
                            :href="bookHref"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-accent text-charcoal font-semibold text-sm hover:bg-accent/90 transition"
                        >
                            Book this service
                        </Link>
                        <WhatsAppButton
                            :number="settings.whatsapp_number"
                            :message="whatsappMessage"
                            variant="outline"
                            label="Chat on WhatsApp"
                        />
                    </div>
                </div>
                <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-cream/10">
                    <img
                        v-if="imageSrc"
                        :src="imageSrc"
                        :alt="service.title"
                        class="h-full w-full object-cover"
                    />
                    <div v-else class="h-full w-full flex items-center justify-center text-cream/40">
                        <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-cream">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2 prose prose-neutral max-w-none prose-headings:font-serif prose-headings:text-primary prose-p:text-charcoal/80" v-html="service.long_description"></div>
                <aside v-if="service.included_items && service.included_items.length" class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm h-fit">
                    <h3 class="font-serif text-xl text-primary mb-5">What's included</h3>
                    <ul class="space-y-3 text-sm text-charcoal/80">
                        <li v-for="(item, idx) in service.included_items" :key="idx" class="flex gap-3">
                            <svg class="h-5 w-5 text-accent flex-none mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ item }}</span>
                        </li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="py-16 bg-accent">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="font-serif text-3xl sm:text-4xl text-charcoal">
                    Ready to begin?
                </h2>
                <p class="mt-3 text-charcoal/80">
                    Send us a quick message and we'll reply with availability within one business day.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <Link
                        :href="bookHref"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-primary text-cream font-semibold text-sm hover:bg-primary/90 transition"
                    >
                        Book {{ service.title }}
                    </Link>
                    <Link
                        :href="route('contact')"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-charcoal/30 text-charcoal font-semibold text-sm hover:border-primary hover:text-primary transition"
                    >
                        Ask a question
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
