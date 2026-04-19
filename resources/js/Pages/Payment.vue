<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    booking: { type: Object, required: true },
});

const formatNaira = (n) => new Intl.NumberFormat('en-NG', {
    style: 'currency', currency: 'NGN', maximumFractionDigits: 0,
}).format(Number(n || 0));
</script>

<template>
    <Head :title="`Pay deposit ${booking.reference} — Blue Dine Cuisines`" />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-14 text-center">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Payment</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Pay deposit</h1>
            </div>
        </section>
        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm text-center">
                    <p class="uppercase tracking-widest text-[10px] text-accent mb-2">Booking</p>
                    <p class="font-serif text-2xl text-primary mb-6">{{ booking.reference }}</p>
                    <p class="uppercase tracking-widest text-[10px] text-accent mb-2">Deposit due</p>
                    <p class="font-serif text-3xl text-primary">{{ formatNaira(booking.deposit_amount) }}</p>
                    <p class="mt-6 text-sm text-charcoal/70">
                        Online payment will be enabled shortly. In the meantime please use the bank details we send with your confirmation email, or chat to us on WhatsApp.
                    </p>
                    <Link
                        :href="route('booking.confirmation', booking.reference)"
                        class="mt-8 inline-flex items-center justify-center px-6 py-3 rounded-full border border-primary text-primary font-semibold text-sm hover:bg-primary hover:text-cream transition"
                    >
                        Back to booking
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
