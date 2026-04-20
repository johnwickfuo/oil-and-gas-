<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    interestLevels: { type: Object, default: () => ({}) },
});

const page = usePage();

const form = useForm({
    name: '',
    email: '',
    phone: '',
    interest_level: 'curious',
    notes: '',
    website: '',
});

function submit() {
    form.post(route('academy.join'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

const teachingPoints = [
    'Nigerian kitchen fundamentals — stocks, seasoning, heat control',
    'Menu planning for small events (6 to 40 guests)',
    'Plating & presentation for intimate dinners',
    'Pastry basics: doughs, batters, plated desserts',
    'Running a home kitchen like a pro (timing, prep lists, mise en place)',
    'Business side — pricing, client communication, sourcing suppliers',
];
</script>

<template>
    <Head title="Blue Dine Academy — Coming Soon" />
    <PublicLayout>
        <section class="relative bg-primary text-cream min-h-[60vh] flex items-center">
            <div class="absolute inset-0 bg-gradient-to-br from-charcoal/80 via-primary/80 to-charcoal/80"></div>
            <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-20 text-center">
                <p class="uppercase tracking-[0.4em] text-xs text-accent mb-6">Coming Soon</p>
                <h1 class="font-serif text-5xl sm:text-6xl leading-tight">Blue Dine Academy</h1>
                <p class="mt-6 text-lg sm:text-xl text-cream/80 max-w-2xl mx-auto">
                    A small-batch culinary school in Port Harcourt. Hands-on training from Chef Eureka and the Blue Dine team — designed for home cooks who want to cook like professionals.
                </p>
            </div>
        </section>

        <section class="py-20 bg-cream">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-12 lg:grid-cols-2">
                    <div>
                        <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Curriculum preview</p>
                        <h2 class="font-serif text-3xl text-primary mb-6">What the Academy will teach</h2>
                        <ul class="space-y-3 text-charcoal/80">
                            <li v-for="(point, idx) in teachingPoints" :key="idx" class="flex gap-3 items-start">
                                <svg class="flex-none h-5 w-5 text-accent mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ point }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm">
                        <div v-if="page.props.flash?.status" class="mb-6 rounded-xl bg-accent/20 border border-accent/40 p-4">
                            <p class="font-semibold text-primary">{{ page.props.flash.status }}</p>
                            <p class="mt-1 text-sm text-charcoal/70">We'll be in touch before registration opens.</p>
                        </div>

                        <template v-else>
                            <p class="uppercase tracking-[0.3em] text-xs text-accent mb-2">Join the waitlist</p>
                            <h3 class="font-serif text-2xl text-primary mb-6">Be first to know</h3>

                            <form @submit.prevent="submit" class="space-y-4">
                                <input v-model="form.website" type="text" class="sr-only" tabindex="-1" autocomplete="off" aria-hidden="true" />

                                <div>
                                    <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Name</label>
                                    <input v-model="form.name" type="text" required
                                           class="w-full rounded-xl border border-primary/20 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary" />
                                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Email</label>
                                    <input v-model="form.email" type="email" required
                                           class="w-full rounded-xl border border-primary/20 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary" />
                                    <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Phone</label>
                                    <input v-model="form.phone" type="tel" required
                                           class="w-full rounded-xl border border-primary/20 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary" />
                                    <p v-if="form.errors.phone" class="mt-1 text-xs text-red-600">{{ form.errors.phone }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Interest level</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <label v-for="(label, key) in interestLevels" :key="key"
                                               class="cursor-pointer rounded-xl border text-center py-2.5 text-sm transition"
                                               :class="form.interest_level === key
                                                   ? 'bg-primary text-cream border-primary'
                                                   : 'border-primary/20 text-charcoal hover:border-primary'">
                                            <input type="radio" :value="key" v-model="form.interest_level" class="sr-only" />
                                            {{ label }}
                                        </label>
                                    </div>
                                    <p v-if="form.errors.interest_level" class="mt-1 text-xs text-red-600">{{ form.errors.interest_level }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Notes <span class="text-charcoal/40">(optional)</span></label>
                                    <textarea v-model="form.notes" rows="3"
                                              class="w-full rounded-xl border border-primary/20 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary"></textarea>
                                </div>

                                <button type="submit" :disabled="form.processing"
                                        class="w-full rounded-full bg-accent text-charcoal font-semibold text-sm px-5 py-3 hover:bg-accent/90 transition disabled:opacity-60">
                                    {{ form.processing ? 'Submitting…' : 'Join waitlist' }}
                                </button>
                            </form>
                        </template>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
