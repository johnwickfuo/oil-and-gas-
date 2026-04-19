<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    booking: { type: Object, required: true },
});

const page = usePage();
const gateway = ref('paystack');
const submitting = ref(false);

const formatNaira = (n) => new Intl.NumberFormat('en-NG', {
    style: 'currency', currency: 'NGN', maximumFractionDigits: 0,
}).format(Number(n || 0));

function submit() {
    submitting.value = true;
    router.post(route('payment.initialize', props.booking.reference), {
        gateway: gateway.value,
    }, {
        onFinish: () => { submitting.value = false; },
    });
}
</script>

<template>
    <Head :title="`Pay deposit ${booking.reference} — Blue Dine Cuisines`" />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-14 text-center">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Secure payment</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Pay your deposit</h1>
                <p class="mt-4 text-cream/80">Choose your preferred gateway. Both are PCI-compliant Nigerian providers.</p>
            </div>
        </section>

        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm">
                    <div class="text-center mb-8">
                        <p class="uppercase tracking-widest text-[10px] text-accent mb-2">Booking</p>
                        <p class="font-serif text-2xl text-primary mb-4">{{ booking.reference }}</p>
                        <p class="uppercase tracking-widest text-[10px] text-accent mb-2">Deposit due (30%)</p>
                        <p class="font-serif text-3xl text-primary">{{ formatNaira(booking.deposit_amount) }}</p>
                        <p class="mt-1 text-xs text-charcoal/60">Estimated total · {{ formatNaira(booking.estimated_total) }}</p>
                    </div>

                    <div v-if="page.props.flash?.error" class="mb-4 rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-700">
                        {{ page.props.flash.error }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-3">
                        <label class="flex items-center gap-4 p-4 rounded-xl border cursor-pointer transition"
                               :class="gateway === 'paystack' ? 'border-primary bg-primary/5' : 'border-primary/10 hover:border-primary/40'">
                            <input type="radio" value="paystack" v-model="gateway" class="text-primary focus:ring-primary">
                            <span class="flex-1">
                                <span class="block font-semibold text-primary">Paystack</span>
                                <span class="block text-xs text-charcoal/70">Cards, bank transfer, USSD, Apple Pay</span>
                            </span>
                        </label>
                        <label class="flex items-center gap-4 p-4 rounded-xl border cursor-pointer transition"
                               :class="gateway === 'flutterwave' ? 'border-primary bg-primary/5' : 'border-primary/10 hover:border-primary/40'">
                            <input type="radio" value="flutterwave" v-model="gateway" class="text-primary focus:ring-primary">
                            <span class="flex-1">
                                <span class="block font-semibold text-primary">Flutterwave</span>
                                <span class="block text-xs text-charcoal/70">Cards, mobile money, bank transfer</span>
                            </span>
                        </label>

                        <button type="submit" :disabled="submitting"
                                class="w-full mt-6 rounded-full bg-accent text-charcoal font-semibold text-sm px-6 py-3 hover:bg-accent/90 transition disabled:opacity-60">
                            {{ submitting ? 'Redirecting…' : `Pay ${formatNaira(booking.deposit_amount)}` }}
                        </button>
                    </form>

                    <p class="mt-4 text-xs text-charcoal/50 text-center">
                        You'll be redirected to the secure gateway to complete payment.
                    </p>
                    <div class="mt-6 text-center">
                        <Link :href="route('booking.confirmation', booking.reference)"
                              class="text-sm text-primary hover:text-accent transition">
                            &larr; Back to booking
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
