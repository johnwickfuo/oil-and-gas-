<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';
</script>

<template>
    <SeoHead
        title="Page Not Found"
        description="The page you were looking for doesn't exist. Explore our weekly menu, recipes and private chef services instead."
        :noindex="true"
    />
    <PublicLayout>
        <section class="bg-primary text-cream py-20">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-4">404</p>
                <h1 class="font-serif text-4xl sm:text-5xl">We couldn't plate that page</h1>
                <p class="mt-6 text-cream/80">
                    The link might be stale or the page has moved. Try one of these instead.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <Link
                        href="/"
                        class="inline-flex items-center rounded-full bg-accent text-charcoal font-semibold text-sm px-5 py-2.5 hover:bg-accent/90 transition"
                    >
                        Back to home
                    </Link>
                    <Link
                        href="/menu"
                        class="inline-flex items-center rounded-full border border-cream/30 text-cream font-semibold text-sm px-5 py-2.5 hover:bg-cream/10 transition"
                    >
                        This week's menu
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
