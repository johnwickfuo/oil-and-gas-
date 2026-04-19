<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    images: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
});

const activeCategory = ref('all');
const activeIndex = ref(null);

const storageUrl = (path) => (path ? `/storage/${path}` : null);

const filtered = computed(() => {
    if (activeCategory.value === 'all') return props.images;
    return props.images.filter((i) => i.category === activeCategory.value);
});

const labelFor = (cat) => (cat ? cat.replace(/[-_]/g, ' ') : '');

const openLightbox = (i) => (activeIndex.value = i);
const closeLightbox = () => (activeIndex.value = null);
const prev = () => {
    if (activeIndex.value === null) return;
    activeIndex.value = (activeIndex.value - 1 + filtered.value.length) % filtered.value.length;
};
const next = () => {
    if (activeIndex.value === null) return;
    activeIndex.value = (activeIndex.value + 1) % filtered.value.length;
};

const currentImage = computed(() =>
    activeIndex.value !== null ? filtered.value[activeIndex.value] : null,
);

const onKey = (e) => {
    if (activeIndex.value === null) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') prev();
    if (e.key === 'ArrowRight') next();
};

onMounted(() => window.addEventListener('keydown', onKey));
onBeforeUnmount(() => window.removeEventListener('keydown', onKey));
</script>

<template>
    <Head title="Gallery — Blue Dine Cuisines" />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-20">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-6">From the kitchen</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Gallery</h1>
                <p class="mt-6 text-cream/80 max-w-2xl">
                    Moments from recent dinners, meal prep drops and small chops trays around Port Harcourt.
                </p>
            </div>
        </section>

        <section class="py-10 bg-cream border-b border-primary/10">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 flex gap-2 overflow-x-auto">
                <button
                    type="button"
                    class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition capitalize"
                    :class="activeCategory === 'all'
                        ? 'bg-primary text-cream'
                        : 'bg-white text-charcoal border border-primary/10 hover:border-primary'"
                    @click="activeCategory = 'all'"
                >
                    All
                </button>
                <button
                    v-for="cat in categories"
                    :key="cat"
                    type="button"
                    class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition capitalize"
                    :class="activeCategory === cat
                        ? 'bg-primary text-cream'
                        : 'bg-white text-charcoal border border-primary/10 hover:border-primary'"
                    @click="activeCategory = cat"
                >
                    {{ labelFor(cat) }}
                </button>
            </div>
        </section>

        <section class="py-16 bg-cream">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div v-if="filtered.length" class="columns-1 sm:columns-2 lg:columns-3 gap-4 space-y-4">
                    <button
                        v-for="(image, idx) in filtered"
                        :key="image.id"
                        type="button"
                        class="block w-full break-inside-avoid rounded-2xl overflow-hidden bg-white border border-primary/5 shadow-sm group"
                        @click="openLightbox(idx)"
                    >
                        <img
                            :src="storageUrl(image.image)"
                            :alt="image.caption || ''"
                            class="w-full h-auto object-cover group-hover:opacity-90 transition"
                            loading="lazy"
                        />
                        <div v-if="image.caption || image.category" class="p-4 text-left">
                            <p v-if="image.category" class="uppercase tracking-widest text-[10px] text-accent mb-1 capitalize">
                                {{ labelFor(image.category) }}
                            </p>
                            <p v-if="image.caption" class="text-sm text-charcoal/80">{{ image.caption }}</p>
                        </div>
                    </button>
                </div>
                <p v-else class="text-charcoal/60 text-center py-20">No photos in this category yet.</p>
            </div>
        </section>

        <transition
            enter-active-class="transition duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="currentImage"
                class="fixed inset-0 z-50 bg-charcoal/90 flex items-center justify-center p-4"
                role="dialog"
                aria-modal="true"
                @click.self="closeLightbox"
            >
                <button
                    type="button"
                    class="absolute top-5 right-5 text-cream/80 hover:text-cream"
                    aria-label="Close"
                    @click="closeLightbox"
                >
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <button
                    v-if="filtered.length > 1"
                    type="button"
                    class="absolute left-5 text-cream/70 hover:text-cream"
                    aria-label="Previous"
                    @click="prev"
                >
                    <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    v-if="filtered.length > 1"
                    type="button"
                    class="absolute right-5 text-cream/70 hover:text-cream"
                    aria-label="Next"
                    @click="next"
                >
                    <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <figure class="max-w-5xl w-full">
                    <img
                        :src="storageUrl(currentImage.image)"
                        :alt="currentImage.caption || ''"
                        class="max-h-[80vh] w-full object-contain rounded-xl"
                    />
                    <figcaption v-if="currentImage.caption" class="mt-4 text-center text-cream/80 text-sm">
                        {{ currentImage.caption }}
                    </figcaption>
                </figure>
            </div>
        </transition>
    </PublicLayout>
</template>
