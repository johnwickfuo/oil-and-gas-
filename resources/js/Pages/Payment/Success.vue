<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    gateway: { type: String, required: true },
    booking: { type: Object, required: true },
});

const formatNaira = (n) => new Intl.NumberFormat('en-NG', {
    style: 'currency', currency: 'NGN', maximumFractionDigits: 0,
}).format(Number(n || 0));

const formattedDate = (iso) => {
    if (!iso) return '';
    return new Date(iso).toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric' });
};
</script>

<template>
    <Head title="Payment received — Blue Dine Cuisines" />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-20 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent text-charcoal mb-6">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Thank you</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Payment received</h1>
                <p class="mt-4 text-cream/80">A receipt is on its way to {{ booking.name }}. We'll reach out shortly to finalise the details.</p>
            </div>
        </section>

        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm">
                    <dl class="divide-y divide-primary/10">
                        <div class="py-3 flex justify-between">
                            <dt class="text-xs uppercase tracking-widest text-charcoal/50">Booking</dt>
                            <dd class="font-semibold text-primary">{{ booking.reference }}</dd>
                        </div>
                        <div v-if="booking.service" class="py-3 flex justify-between">
                            <dt class="text-xs uppercase tracking-widest text-charcoal/50">Service</dt>
                            <dd class="text-charcoal/90">{{ booking.service.title }}</dd>
                        </div>
                        <div v-if="booking.event_date" class="py-3 flex justify-between">
                            <dt class="text-xs uppercase tracking-widest text-charcoal/50">Event</dt>
                            <dd class="text-charcoal/90">{{ formattedDate(booking.event_date) }} · {{ booking.event_time }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-xs uppercase tracking-widest text-charcoal/50">Deposit paid</dt>
                            <dd class="font-semibold text-primary">{{ formatNaira(booking.deposit_amount) }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-xs uppercase tracking-widest text-charcoal/50">Gateway</dt>
                            <dd class="capitalize text-charcoal/90">{{ gateway }}</dd>
                        </div>
                    </dl>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <Link :href="route('booking.confirmation', booking.reference)"
                              class="flex-1 inline-flex items-center justify-center px-5 py-3 rounded-full bg-primary text-cream font-semibold text-sm hover:bg-primary/90 transition">
                            View booking
                        </Link>
                        <Link :href="route('home')"
                              class="flex-1 inline-flex items-center justify-center px-5 py-3 rounded-full border border-primary/30 text-primary font-semibold text-sm hover:border-primary transition">
                            Back home
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
