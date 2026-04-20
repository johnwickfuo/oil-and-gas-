<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';

const props = defineProps({
    services: { type: Array, default: () => [] },
    config: { type: Object, default: () => ({ addons: [], locations: [], deposit_percentage: 30 }) },
    initial: { type: Object, default: () => ({}) },
});

const selectedSlug = ref(props.initial?.service_slug || props.services[0]?.slug || '');
const guests = ref(Math.max(2, props.services.find((s) => s.slug === selectedSlug.value)?.minimum_guests || 2));
const selectedAddons = ref([]);
const selectedLocation = ref(props.config.locations[0]?.key || '');

const selectedService = computed(() => props.services.find((s) => s.slug === selectedSlug.value) || null);

watch(selectedService, (s) => {
    if (!s) return;
    if (guests.value < s.minimum_guests) guests.value = s.minimum_guests;
});

const baseSubtotal = computed(() => {
    const s = selectedService.value;
    if (!s) return 0;
    const perGuest = s.base_per_guest ?? 0;
    return perGuest * Math.max(s.minimum_guests ?? 1, Number(guests.value) || 0);
});

const addonLines = computed(() =>
    props.config.addons.filter((a) => selectedAddons.value.includes(a.key)),
);

const addonsSubtotal = computed(() =>
    addonLines.value.reduce((sum, a) => sum + a.price, 0),
);

const locationFee = computed(() => {
    const loc = props.config.locations.find((l) => l.key === selectedLocation.value);
    return loc ? loc.logistics_fee : 0;
});

const total = computed(() => baseSubtotal.value + addonsSubtotal.value + locationFee.value);
const deposit = computed(() => Math.round(total.value * (props.config.deposit_percentage / 100)));

const formatNaira = (n) => new Intl.NumberFormat('en-NG', {
    style: 'currency', currency: 'NGN', maximumFractionDigits: 0,
}).format(Number(n || 0));

const goToBooking = () => {
    router.get(route('booking.create'), {
        service: selectedSlug.value,
        guests: guests.value,
        location: selectedLocation.value,
        addons: selectedAddons.value,
    });
};

const summaryOpen = ref(false);
</script>

<template>
    <SeoHead
        title="Pricing Calculator"
        description="Estimate your Blue Dine booking — private chef, meal prep and small chops catering in Port Harcourt. Get a live price with location and add-ons."
    />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-16">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-4">Estimate</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Pricing calculator</h1>
                <p class="mt-4 text-cream/80 max-w-2xl">
                    Get an instant estimate for your event. The deposit ({{ config.deposit_percentage }}%) confirms the booking;
                    the balance is due on the day.
                </p>
            </div>
        </section>

        <section class="py-14 bg-cream">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white border border-primary/5 rounded-2xl p-6 sm:p-8 shadow-sm">
                        <p class="uppercase tracking-widest text-[10px] text-accent mb-2">Step 1</p>
                        <h2 class="font-serif text-xl text-primary mb-5">Choose a service</h2>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label
                                v-for="s in services"
                                :key="s.slug"
                                class="flex cursor-pointer gap-3 rounded-xl border p-4 transition"
                                :class="selectedSlug === s.slug
                                    ? 'border-primary bg-primary/5'
                                    : 'border-primary/10 hover:border-primary/40'"
                            >
                                <input
                                    type="radio"
                                    class="mt-1 text-primary focus:ring-primary"
                                    :value="s.slug"
                                    v-model="selectedSlug"
                                />
                                <div>
                                    <p class="font-serif text-primary">{{ s.title }}</p>
                                    <p class="text-sm text-charcoal/70 mt-1">{{ s.short_description }}</p>
                                    <p v-if="s.base_per_guest" class="text-xs text-charcoal/60 mt-2">
                                        {{ formatNaira(s.base_per_guest) }} / guest · min {{ s.minimum_guests }}
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-white border border-primary/5 rounded-2xl p-6 sm:p-8 shadow-sm">
                        <p class="uppercase tracking-widest text-[10px] text-accent mb-2">Step 2</p>
                        <h2 class="font-serif text-xl text-primary mb-5">How many guests?</h2>
                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                class="h-10 w-10 rounded-full border border-primary/20 text-primary hover:bg-primary/5"
                                @click="guests = Math.max(1, Number(guests) - 1)"
                            >−</button>
                            <input
                                type="number"
                                v-model.number="guests"
                                min="1"
                                max="200"
                                class="w-24 rounded-xl border border-primary/10 bg-cream/50 px-4 py-2 text-center focus:border-primary focus:ring-primary"
                            />
                            <button
                                type="button"
                                class="h-10 w-10 rounded-full border border-primary/20 text-primary hover:bg-primary/5"
                                @click="guests = Math.min(200, Number(guests) + 1)"
                            >+</button>
                            <p class="text-sm text-charcoal/60">
                                min {{ selectedService?.minimum_guests ?? 1 }}, max 200
                            </p>
                        </div>
                    </div>

                    <div class="bg-white border border-primary/5 rounded-2xl p-6 sm:p-8 shadow-sm">
                        <p class="uppercase tracking-widest text-[10px] text-accent mb-2">Step 3</p>
                        <h2 class="font-serif text-xl text-primary mb-5">Add-ons</h2>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label
                                v-for="a in config.addons"
                                :key="a.key"
                                class="flex cursor-pointer gap-3 rounded-xl border p-4 transition"
                                :class="selectedAddons.includes(a.key)
                                    ? 'border-primary bg-primary/5'
                                    : 'border-primary/10 hover:border-primary/40'"
                            >
                                <input
                                    type="checkbox"
                                    class="mt-1 rounded text-primary focus:ring-primary"
                                    :value="a.key"
                                    v-model="selectedAddons"
                                />
                                <div class="flex-1">
                                    <div class="flex items-baseline justify-between gap-2">
                                        <p class="font-serif text-primary">{{ a.label }}</p>
                                        <p class="text-sm font-semibold text-primary">{{ formatNaira(a.price) }}</p>
                                    </div>
                                    <p class="text-sm text-charcoal/70 mt-1">{{ a.description }}</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-white border border-primary/5 rounded-2xl p-6 sm:p-8 shadow-sm">
                        <p class="uppercase tracking-widest text-[10px] text-accent mb-2">Step 4</p>
                        <h2 class="font-serif text-xl text-primary mb-5">Service area</h2>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label
                                v-for="l in config.locations"
                                :key="l.key"
                                class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border p-4 transition"
                                :class="selectedLocation === l.key
                                    ? 'border-primary bg-primary/5'
                                    : 'border-primary/10 hover:border-primary/40'"
                            >
                                <div class="flex items-center gap-3">
                                    <input
                                        type="radio"
                                        class="text-primary focus:ring-primary"
                                        :value="l.key"
                                        v-model="selectedLocation"
                                    />
                                    <span class="font-medium text-charcoal">{{ l.label }}</span>
                                </div>
                                <span class="text-sm text-charcoal/60">
                                    {{ l.logistics_fee === 0 ? 'Included' : '+ ' + formatNaira(l.logistics_fee) }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <aside class="hidden lg:block">
                    <div class="sticky top-28 bg-white border border-primary/5 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-serif text-xl text-primary mb-4">Your estimate</h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-charcoal/70">{{ selectedService?.title || '—' }} × {{ guests }}</dt>
                                <dd class="font-semibold text-charcoal">{{ formatNaira(baseSubtotal) }}</dd>
                            </div>
                            <div v-for="a in addonLines" :key="a.key" class="flex justify-between">
                                <dt class="text-charcoal/70">{{ a.label }}</dt>
                                <dd class="font-semibold text-charcoal">{{ formatNaira(a.price) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-charcoal/70">Logistics</dt>
                                <dd class="font-semibold text-charcoal">{{ formatNaira(locationFee) }}</dd>
                            </div>
                        </dl>
                        <div class="border-t border-primary/10 mt-5 pt-5 space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-charcoal/80 font-medium">Estimated total</dt>
                                <dd class="font-serif text-2xl text-primary">{{ formatNaira(total) }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-charcoal/60">Deposit ({{ config.deposit_percentage }}%)</dt>
                                <dd class="font-semibold text-accent">{{ formatNaira(deposit) }}</dd>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="mt-6 w-full inline-flex items-center justify-center rounded-full bg-primary text-cream font-semibold text-sm px-5 py-3 hover:bg-primary/90 transition"
                            @click="goToBooking"
                        >
                            Continue to booking
                        </button>
                    </div>
                </aside>
            </div>
        </section>

        <div class="lg:hidden fixed inset-x-0 bottom-0 z-30 bg-white border-t border-primary/10 shadow-[0_-4px_12px_rgba(0,0,0,0.04)]">
            <button
                type="button"
                class="w-full flex items-center justify-between px-5 py-4"
                @click="summaryOpen = !summaryOpen"
            >
                <div class="text-left">
                    <p class="text-[10px] uppercase tracking-widest text-charcoal/60">Estimated total</p>
                    <p class="font-serif text-xl text-primary">{{ formatNaira(total) }}</p>
                </div>
                <svg class="h-5 w-5 text-primary transition" :class="summaryOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                </svg>
            </button>
            <transition
                enter-active-class="transition duration-200"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="summaryOpen" class="px-5 pb-5 space-y-3 text-sm">
                    <div class="flex justify-between"><span>{{ selectedService?.title || '—' }} × {{ guests }}</span><span class="font-semibold">{{ formatNaira(baseSubtotal) }}</span></div>
                    <div v-for="a in addonLines" :key="a.key" class="flex justify-between"><span>{{ a.label }}</span><span class="font-semibold">{{ formatNaira(a.price) }}</span></div>
                    <div class="flex justify-between"><span>Logistics</span><span class="font-semibold">{{ formatNaira(locationFee) }}</span></div>
                    <div class="flex justify-between border-t border-primary/10 pt-3"><span class="text-charcoal/60">Deposit</span><span class="font-semibold text-accent">{{ formatNaira(deposit) }}</span></div>
                </div>
            </transition>
            <div class="px-5 pb-5">
                <button
                    type="button"
                    class="w-full inline-flex items-center justify-center rounded-full bg-primary text-cream font-semibold text-sm px-5 py-3 hover:bg-primary/90 transition"
                    @click="goToBooking"
                >
                    Continue to booking
                </button>
            </div>
        </div>
    </PublicLayout>
</template>
