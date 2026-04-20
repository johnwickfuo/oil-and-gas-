<script setup>
import { computed, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';

const props = defineProps({
    services: { type: Array, default: () => [] },
    config: { type: Object, default: () => ({ addons: [], locations: [], deposit_percentage: 30 }) },
    initial: { type: Object, default: () => ({}) },
});

const step = ref(1);
const totalSteps = 5;

const form = useForm({
    service_slug: props.initial?.service_slug || props.services[0]?.slug || '',
    event_date: '',
    event_time: '',
    guests: props.initial?.guests || props.services.find((s) => s.slug === (props.initial?.service_slug || props.services[0]?.slug))?.minimum_guests || 2,
    location: props.initial?.location || props.config.locations[0]?.key || '',
    addons: props.initial?.addons || [],
    menu_preferences: '',
    dietary_notes: '',
    special_requests: '',
    name: '',
    email: '',
    phone: '',
    website: '',
});

const selectedService = computed(() => props.services.find((s) => s.slug === form.service_slug) || null);

watch(
    () => form.service_slug,
    () => {
        const s = selectedService.value;
        if (s && form.guests < s.minimum_guests) form.guests = s.minimum_guests;
    },
);

const baseSubtotal = computed(() => {
    const s = selectedService.value;
    if (!s) return 0;
    return (s.base_per_guest ?? 0) * Math.max(s.minimum_guests ?? 1, Number(form.guests) || 0);
});
const addonLines = computed(() => props.config.addons.filter((a) => form.addons.includes(a.key)));
const addonsSubtotal = computed(() => addonLines.value.reduce((sum, a) => sum + a.price, 0));
const locationFee = computed(() => props.config.locations.find((l) => l.key === form.location)?.logistics_fee ?? 0);
const total = computed(() => baseSubtotal.value + addonsSubtotal.value + locationFee.value);
const deposit = computed(() => Math.round(total.value * (props.config.deposit_percentage / 100)));

const formatNaira = (n) => new Intl.NumberFormat('en-NG', {
    style: 'currency', currency: 'NGN', maximumFractionDigits: 0,
}).format(Number(n || 0));

const locationLabel = computed(() => props.config.locations.find((l) => l.key === form.location)?.label ?? '—');

const canAdvance = computed(() => {
    if (step.value === 1) return !!form.service_slug;
    if (step.value === 2) return !!form.event_date && !!form.event_time && form.guests >= 1 && !!form.location;
    if (step.value === 3) return true;
    if (step.value === 4) return !!form.name && !!form.email && !!form.phone;
    return true;
});

const next = () => {
    if (canAdvance.value && step.value < totalSteps) step.value += 1;
};
const back = () => {
    if (step.value > 1) step.value -= 1;
};

const submit = () => {
    form.post(route('booking.store'), {
        preserveScroll: true,
    });
};

const steps = [
    { n: 1, label: 'Service' },
    { n: 2, label: 'Event' },
    { n: 3, label: 'Preferences' },
    { n: 4, label: 'Contact' },
    { n: 5, label: 'Review' },
];

const minDate = new Date(Date.now() + 86400000).toISOString().split('T')[0];
</script>

<template>
    <SeoHead
        title="Book a Chef"
        description="Book Blue Dine Cuisines — private chef dinners, meal prep and small chops catering in Port Harcourt. Pick a service, date and guest count."
    />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-14">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-4">Booking</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Plan your event</h1>
                <p class="mt-4 text-cream/80 max-w-2xl">
                    A few quick details and we'll come back with availability within one business day.
                </p>
            </div>
        </section>

        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <ol class="flex items-center justify-between mb-8 gap-2">
                    <li v-for="s in steps" :key="s.n" class="flex-1 text-center">
                        <div
                            class="h-2 rounded-full transition"
                            :class="step >= s.n ? 'bg-primary' : 'bg-primary/10'"
                        ></div>
                        <p class="mt-2 text-xs uppercase tracking-widest" :class="step >= s.n ? 'text-primary font-semibold' : 'text-charcoal/40'">
                            {{ s.label }}
                        </p>
                    </li>
                </ol>

                <form class="bg-white border border-primary/5 rounded-2xl p-6 sm:p-8 shadow-sm" @submit.prevent="step === totalSteps ? submit() : next()">
                    <div class="sr-only" aria-hidden="true">
                        <label for="website">Website</label>
                        <input id="website" v-model="form.website" type="text" tabindex="-1" autocomplete="off" />
                    </div>

                    <!-- Step 1: Service -->
                    <div v-if="step === 1">
                        <h2 class="font-serif text-xl text-primary mb-5">Choose a service</h2>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label
                                v-for="s in services"
                                :key="s.slug"
                                class="flex cursor-pointer gap-3 rounded-xl border p-4 transition"
                                :class="form.service_slug === s.slug
                                    ? 'border-primary bg-primary/5'
                                    : 'border-primary/10 hover:border-primary/40'"
                            >
                                <input
                                    type="radio"
                                    class="mt-1 text-primary focus:ring-primary"
                                    :value="s.slug"
                                    v-model="form.service_slug"
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
                        <p v-if="form.errors.service_slug" class="text-xs text-red-600 mt-3">{{ form.errors.service_slug }}</p>
                    </div>

                    <!-- Step 2: Event details -->
                    <div v-if="step === 2" class="grid gap-5">
                        <h2 class="font-serif text-xl text-primary">Event details</h2>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Event date</label>
                                <input
                                    v-model="form.event_date"
                                    type="date"
                                    :min="minDate"
                                    required
                                    class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"
                                />
                                <p v-if="form.errors.event_date" class="text-xs text-red-600 mt-1">{{ form.errors.event_date }}</p>
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Event time</label>
                                <input
                                    v-model="form.event_time"
                                    type="time"
                                    required
                                    class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"
                                />
                                <p v-if="form.errors.event_time" class="text-xs text-red-600 mt-1">{{ form.errors.event_time }}</p>
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Guests</label>
                                <input
                                    v-model.number="form.guests"
                                    type="number"
                                    min="1"
                                    max="200"
                                    required
                                    class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"
                                />
                                <p v-if="form.errors.guests" class="text-xs text-red-600 mt-1">{{ form.errors.guests }}</p>
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Location</label>
                                <select
                                    v-model="form.location"
                                    required
                                    class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"
                                >
                                    <option v-for="l in config.locations" :key="l.key" :value="l.key">
                                        {{ l.label }}{{ l.logistics_fee ? ' (+' + formatNaira(l.logistics_fee) + ')' : '' }}
                                    </option>
                                </select>
                                <p v-if="form.errors.location" class="text-xs text-red-600 mt-1">{{ form.errors.location }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Preferences -->
                    <div v-if="step === 3" class="grid gap-5">
                        <h2 class="font-serif text-xl text-primary">Preferences</h2>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Add-ons (optional)</label>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label
                                    v-for="a in config.addons"
                                    :key="a.key"
                                    class="flex cursor-pointer gap-3 rounded-xl border p-4 transition"
                                    :class="form.addons.includes(a.key)
                                        ? 'border-primary bg-primary/5'
                                        : 'border-primary/10 hover:border-primary/40'"
                                >
                                    <input type="checkbox" :value="a.key" v-model="form.addons" class="mt-1 rounded text-primary focus:ring-primary" />
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <p class="font-serif text-primary">{{ a.label }}</p>
                                            <p class="text-sm font-semibold text-primary">{{ formatNaira(a.price) }}</p>
                                        </div>
                                        <p class="text-sm text-charcoal/70 mt-1">{{ a.description }}</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Menu preferences (optional)</label>
                            <textarea v-model="form.menu_preferences" rows="3" class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Dietary notes (optional)</label>
                            <textarea v-model="form.dietary_notes" rows="2" class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Special requests (optional)</label>
                            <textarea v-model="form.special_requests" rows="2" class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"></textarea>
                        </div>
                    </div>

                    <!-- Step 4: Contact -->
                    <div v-if="step === 4" class="grid gap-5">
                        <h2 class="font-serif text-xl text-primary">Contact information</h2>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Full name</label>
                                <input v-model="form.name" type="text" required class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary" />
                                <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Email</label>
                                <input v-model="form.email" type="email" required class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary" />
                                <p v-if="form.errors.email" class="text-xs text-red-600 mt-1">{{ form.errors.email }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Phone</label>
                            <input v-model="form.phone" type="tel" required class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary" />
                            <p v-if="form.errors.phone" class="text-xs text-red-600 mt-1">{{ form.errors.phone }}</p>
                        </div>
                    </div>

                    <!-- Step 5: Review -->
                    <div v-if="step === 5">
                        <h2 class="font-serif text-xl text-primary mb-5">Review your booking</h2>
                        <div class="grid sm:grid-cols-2 gap-6 text-sm text-charcoal/80">
                            <div class="space-y-2">
                                <p><span class="uppercase tracking-widest text-[10px] text-accent block">Service</span>{{ selectedService?.title }}</p>
                                <p><span class="uppercase tracking-widest text-[10px] text-accent block">Date & time</span>{{ form.event_date }} at {{ form.event_time }}</p>
                                <p><span class="uppercase tracking-widest text-[10px] text-accent block">Guests</span>{{ form.guests }}</p>
                                <p><span class="uppercase tracking-widest text-[10px] text-accent block">Location</span>{{ locationLabel }}</p>
                            </div>
                            <div class="space-y-2">
                                <p><span class="uppercase tracking-widest text-[10px] text-accent block">Name</span>{{ form.name }}</p>
                                <p><span class="uppercase tracking-widest text-[10px] text-accent block">Email</span>{{ form.email }}</p>
                                <p><span class="uppercase tracking-widest text-[10px] text-accent block">Phone</span>{{ form.phone }}</p>
                            </div>
                        </div>
                        <dl class="mt-8 space-y-3 text-sm border-t border-primary/10 pt-6">
                            <div class="flex justify-between">
                                <dt class="text-charcoal/70">{{ selectedService?.title }} × {{ form.guests }}</dt>
                                <dd class="font-semibold text-charcoal">{{ formatNaira(baseSubtotal) }}</dd>
                            </div>
                            <div v-for="a in addonLines" :key="a.key" class="flex justify-between">
                                <dt class="text-charcoal/70">{{ a.label }}</dt>
                                <dd class="font-semibold text-charcoal">{{ formatNaira(a.price) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-charcoal/70">Logistics ({{ locationLabel }})</dt>
                                <dd class="font-semibold text-charcoal">{{ formatNaira(locationFee) }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-primary/10 pt-3">
                                <dt class="font-medium">Estimated total</dt>
                                <dd class="font-serif text-2xl text-primary">{{ formatNaira(total) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-charcoal/60">Deposit ({{ config.deposit_percentage }}%)</dt>
                                <dd class="font-semibold text-accent">{{ formatNaira(deposit) }}</dd>
                            </div>
                        </dl>
                        <p class="mt-4 text-xs text-charcoal/60">
                            The deposit confirms your booking. The balance is due on the day of service.
                        </p>
                    </div>

                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-primary/10 pt-6">
                        <button v-if="step > 1" type="button" class="text-sm font-semibold text-charcoal/70 hover:text-primary" @click="back">
                            &larr; Back
                        </button>
                        <span v-else></span>
                        <button
                            type="submit"
                            :disabled="!canAdvance || form.processing"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-primary text-cream font-semibold text-sm hover:bg-primary/90 transition disabled:opacity-60"
                        >
                            <span v-if="form.processing">Submitting…</span>
                            <span v-else-if="step < totalSteps">Continue</span>
                            <span v-else>Submit booking</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </PublicLayout>
</template>
