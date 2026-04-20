<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';
import WhatsAppButton from '@/Components/WhatsAppButton.vue';

const props = defineProps({
    booking: { type: Object, required: true },
    settings: { type: Object, default: () => ({}) },
});

const formatNaira = (n) => new Intl.NumberFormat('en-NG', {
    style: 'currency', currency: 'NGN', maximumFractionDigits: 0,
}).format(Number(n || 0));

const whatsappMessage = `Hi Blue Dine, my booking reference is ${props.booking.reference}.`;
</script>

<template>
    <SeoHead
        :title="`Booking ${booking.reference}`"
        description="Your Blue Dine Cuisines booking confirmation."
        noindex
    />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16 text-center">
                <div class="mx-auto h-14 w-14 rounded-full bg-accent/20 flex items-center justify-center mb-6">
                    <svg class="h-7 w-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Booking received</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Thanks, {{ booking.name }}!</h1>
                <p class="mt-4 text-cream/80">
                    Your booking reference is
                    <span class="font-semibold text-accent">{{ booking.reference }}</span>.
                    We'll confirm availability within one business day.
                </p>
            </div>
        </section>

        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm">
                    <h2 class="font-serif text-xl text-primary mb-5">Summary</h2>
                    <dl class="grid sm:grid-cols-2 gap-4 text-sm text-charcoal/80">
                        <div>
                            <dt class="uppercase tracking-widest text-[10px] text-accent mb-1">Service</dt>
                            <dd>{{ booking.service?.title || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-widest text-[10px] text-accent mb-1">Date & time</dt>
                            <dd>{{ booking.event_date }} at {{ booking.event_time }}</dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-widest text-[10px] text-accent mb-1">Guests</dt>
                            <dd>{{ booking.guests }}</dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-widest text-[10px] text-accent mb-1">Location</dt>
                            <dd>{{ booking.location }}</dd>
                        </div>
                    </dl>
                    <div class="mt-6 border-t border-primary/10 pt-5 flex flex-col sm:flex-row sm:justify-between gap-3">
                        <div>
                            <p class="uppercase tracking-widest text-[10px] text-accent mb-1">Estimated total</p>
                            <p class="font-serif text-2xl text-primary">{{ formatNaira(booking.estimated_total) }}</p>
                        </div>
                        <div class="sm:text-right">
                            <p class="uppercase tracking-widest text-[10px] text-accent mb-1">Deposit due</p>
                            <p class="font-serif text-2xl text-accent">{{ formatNaira(booking.deposit_amount) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm">
                    <h2 class="font-serif text-xl text-primary mb-3">Next steps</h2>
                    <ol class="list-decimal pl-5 space-y-2 text-sm text-charcoal/80">
                        <li>Pay the {{ Math.round(booking.deposit_amount / booking.estimated_total * 100) }}% deposit to confirm the booking.</li>
                        <li>We'll reply to {{ booking.email }} with your menu and final details.</li>
                        <li>Settle the balance on the day of service.</li>
                    </ol>
                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        <Link
                            :href="route('payment.show', booking.reference)"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-primary text-cream font-semibold text-sm hover:bg-primary/90 transition"
                        >
                            Pay deposit now
                        </Link>
                        <WhatsAppButton
                            :number="settings.whatsapp_number"
                            :message="whatsappMessage"
                            variant="outline"
                            label="Chat on WhatsApp"
                        />
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
