<script setup>
import { computed, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    resources: { type: Array, required: true },
});

const page = usePage();
const modalResource = ref(null);
const name = ref('');
const email = ref('');
const website = ref('');

const csrfToken = computed(() => {
    if (typeof document === 'undefined') return '';
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
});

function openModal(resource) {
    modalResource.value = resource;
    name.value = '';
    email.value = '';
    website.value = '';
}

function closeModal() {
    modalResource.value = null;
}

const storageUrl = (path) => (path ? `/storage/${path}` : null);
</script>

<template>
    <Head title="Free Resources — Blue Dine Cuisines" />
    <PublicLayout>
        <section class="bg-primary text-cream py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Free downloads</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Recipe cards & meal planners</h1>
                <p class="mt-4 text-cream/80 max-w-2xl">Drop your email to grab printable PDFs we've put together for our community.</p>
            </div>
        </section>

        <section v-if="page.props.errors?.email" class="bg-red-50 border-y border-red-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3 text-sm text-red-700">
                {{ page.props.errors.email }}
            </div>
        </section>

        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-if="resources.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <article v-for="resource in resources" :key="resource.id"
                             class="bg-white rounded-2xl overflow-hidden border border-primary/5 shadow-sm flex flex-col">
                        <div class="aspect-[16/10] bg-primary/10 overflow-hidden">
                            <img v-if="resource.cover_image" :src="storageUrl(resource.cover_image)" :alt="resource.title"
                                 class="h-full w-full object-cover" />
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="font-serif text-xl text-primary">{{ resource.title }}</h3>
                            <p class="mt-2 text-sm text-charcoal/70 flex-1">{{ resource.description }}</p>
                            <p class="mt-3 text-[10px] uppercase tracking-widest text-charcoal/40">
                                {{ resource.download_count }} download{{ resource.download_count === 1 ? '' : 's' }}
                            </p>
                            <button type="button" @click="openModal(resource)"
                                    class="mt-5 inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-accent text-charcoal font-semibold text-sm hover:bg-accent/90 transition">
                                Download
                            </button>
                        </div>
                    </article>
                </div>
                <p v-else class="text-charcoal/60">New resources coming soon.</p>
            </div>
        </section>

        <div v-if="modalResource" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-charcoal/60" @click.self="closeModal">
            <div class="bg-white rounded-2xl max-w-md w-full p-8 shadow-2xl">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <p class="uppercase tracking-[0.3em] text-xs text-accent mb-2">Download</p>
                        <h2 class="font-serif text-2xl text-primary leading-snug">{{ modalResource.title }}</h2>
                    </div>
                    <button type="button" @click="closeModal" aria-label="Close" class="text-charcoal/40 hover:text-charcoal">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form :action="route('resources.download', modalResource.slug)" method="POST" class="space-y-4" @submit="closeModal">
                    <input type="hidden" name="_token" :value="csrfToken" />
                    <div>
                        <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Name <span class="text-charcoal/40">(optional)</span></label>
                        <input v-model="name" name="name" type="text"
                               class="w-full rounded-xl border border-primary/20 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary" />
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-[0.2em] text-accent mb-2">Email <span class="text-red-500">*</span></label>
                        <input v-model="email" name="email" type="email" required
                               class="w-full rounded-xl border border-primary/20 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary" />
                    </div>
                    <input v-model="website" name="website" type="text" class="sr-only" tabindex="-1" autocomplete="off" aria-hidden="true" />
                    <p class="text-xs text-charcoal/60">
                        We'll email you occasional recipes and menu updates. No spam, unsubscribe anytime.
                    </p>
                    <button type="submit"
                            class="w-full rounded-full bg-primary text-cream font-semibold text-sm px-5 py-3 hover:bg-primary/90 transition">
                        Email me the download
                    </button>
                </form>
            </div>
        </div>
    </PublicLayout>
</template>
