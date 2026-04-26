<script setup>
import { computed, ref } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';
import WhatsAppButton from '@/Components/WhatsAppButton.vue';

const props = defineProps({
    items: { type: Array, default: () => [] },
    categories: { type: Object, default: () => ({}) },
    weekRange: { type: String, default: '' },
    weekOf: { type: String, default: '' },
    settings: { type: Object, default: () => ({}) },
});

const activeCategory = ref('all');

const tabs = computed(() => [
    { key: 'all', label: 'All' },
    ...Object.entries(props.categories).map(([key, label]) => ({ key, label })),
]);

const visibleItems = computed(() => {
    if (activeCategory.value === 'all') return props.items;
    return props.items.filter((i) => i.category === activeCategory.value);
});

const formatNaira = (value) => new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    maximumFractionDigits: 0,
}).format(Number(value ?? 0));

const storageUrl = (path) => (path ? `/storage/${path}` : null);

const orderMessage = (item) => `Hi Blue Dine, I'd like to order "${item.name}" (${formatNaira(item.price)}) from this week's menu.`;
</script>

<template>
    <SeoHead
        title="This Week's Menu"
        description="Seasonal weekly menu from Blue Dine Cuisines — healthy meal delivery in Port Harcourt, small chops catering and curated dinners."
    />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-20">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-6">On the table</p>
                <h1 class="font-serif text-4xl sm:text-5xl">This Week's Menu</h1>
                <p v-if="weekRange" class="mt-4 text-cream/80">Week of {{ weekRange }}</p>
                <p class="mt-6 text-cream/80 max-w-2xl">
                    Order directly on WhatsApp and we'll confirm availability, delivery window and payment within the hour.
                </p>
            </div>
        </section>

        <section class="sticky top-20 z-20 bg-cream/95 backdrop-blur border-b border-primary/10">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-4 flex gap-2 overflow-x-auto">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    type="button"
                    class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition"
                    :class="activeCategory === tab.key
                        ? 'bg-primary text-cream'
                        : 'bg-white text-charcoal border border-primary/10 hover:border-primary'"
                    @click="activeCategory = tab.key"
                >
                    {{ tab.label }}
                </button>
            </div>
        </section>

        <section class="py-16 bg-cream">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div v-if="visibleItems.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <article
                        v-for="item in visibleItems"
                        :key="item.id"
                        class="bg-white rounded-2xl overflow-hidden border border-primary/5 shadow-sm flex flex-col"
                    >
                        <div class="aspect-[4/3] bg-primary/10 overflow-hidden">
                            <img loading="lazy" decoding="async"
                                v-if="item.photo"
                                :src="storageUrl(item.photo)"
                                :alt="item.name"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="h-full w-full flex items-center justify-center text-primary/30">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <p class="uppercase tracking-widest text-[10px] text-accent mb-1">{{ categories[item.category] || item.category }}</p>
                            <h3 class="font-serif text-lg text-primary">{{ item.name }}</h3>
                            <p v-if="item.description" class="mt-2 text-sm text-charcoal/70 leading-relaxed flex-1">{{ item.description }}</p>
                            <div class="mt-5 flex items-center justify-between gap-3">
                                <p class="font-semibold text-primary">{{ formatNaira(item.price) }}</p>
                                <WhatsAppButton
                                    :number="settings.whatsapp_number"
                                    :message="orderMessage(item)"
                                    label="Order"
                                />
                            </div>
                        </div>
                    </article>
                </div>
                <div v-else class="text-center py-20">
                    <p class="font-serif text-2xl text-primary mb-2">No dishes here yet</p>
                    <p class="text-charcoal/60">This week's menu is still being plated. Check back shortly.</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
