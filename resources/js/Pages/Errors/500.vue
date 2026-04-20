<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';
</script>

<template>
    <SeoHead
        title="Something went wrong"
        description="Our kitchen hit a snag. Please try again shortly or get in touch."
        :noindex="true"
    />
    <PublicLayout>
        <section class="bg-primary text-cream py-20">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-4">500</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Something burnt in the kitchen</h1>
                <p class="mt-6 text-cream/80">
                    We ran into an unexpected error. Please refresh in a moment, or send us a note on WhatsApp and we'll sort it out.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <Link
                        href="/"
                        class="inline-flex items-center rounded-full bg-accent text-charcoal font-semibold text-sm px-5 py-2.5 hover:bg-accent/90 transition"
                    >
                        Back to home
                    </Link>
                    <Link
                        href="/contact"
                        class="inline-flex items-center rounded-full border border-cream/30 text-cream font-semibold text-sm px-5 py-2.5 hover:bg-cream/10 transition"
                    >
                        Contact us
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
