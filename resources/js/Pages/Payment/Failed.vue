<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';

defineProps({
    gateway: { type: String, required: true },
});

const page = usePage();
</script>

<template>
    <SeoHead title="Payment could not be completed" description="Your Blue Dine payment was not completed." noindex />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-20 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-6">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Payment issue</p>
                <h1 class="font-serif text-4xl sm:text-5xl">We couldn't confirm your payment</h1>
                <p class="mt-4 text-cream/80 capitalize">{{ gateway }} did not complete the transaction.</p>
                <p v-if="page.props.flash?.error" class="mt-2 text-cream/70">{{ page.props.flash.error }}</p>
            </div>
        </section>

        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm text-center">
                    <p class="text-charcoal/70">
                        No charge was applied if the payment was cancelled. You can try again or reach out on WhatsApp and we'll handle it manually.
                    </p>
                    <div class="mt-8">
                        <Link :href="route('home')"
                              class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-primary text-primary font-semibold text-sm hover:bg-primary hover:text-cream transition">
                            Back home
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
