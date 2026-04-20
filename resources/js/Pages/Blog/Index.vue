<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';

const props = defineProps({
    featured: { type: Object, default: null },
    posts: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.q || '');
const selectedCategory = ref(props.filters.category || '');

let searchDebounce = null;
watch(search, (value) => {
    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => apply({ q: value || null }), 300);
});

function apply(extra = {}) {
    router.get(route('blog.index'), {
        q: extra.q !== undefined ? extra.q : (search.value || null),
        category: extra.category !== undefined ? extra.category : (selectedCategory.value || null),
    }, { preserveScroll: true, preserveState: true, replace: true });
}

function pickCategory(cat) {
    selectedCategory.value = selectedCategory.value === cat ? '' : cat;
    apply({ category: selectedCategory.value || null });
}

const storageUrl = (path) => (path ? `/storage/${path}` : null);
const formattedDate = (iso) => iso ? new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '';
</script>

<template>
    <SeoHead
        title="Journal"
        description="Techniques, seasonal menus and kitchen stories from Blue Dine Cuisines — private chef and meal prep in Port Harcourt."
    />
    <PublicLayout>
        <section class="bg-primary text-cream py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Journal</p>
                <h1 class="font-serif text-4xl sm:text-5xl">From the Blue Dine kitchen</h1>
                <p class="mt-4 text-cream/80 max-w-2xl">Techniques, seasonal menus, event notes and kitchen stories.</p>

                <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:items-center">
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Search articles…"
                        class="flex-1 rounded-full border-0 bg-cream/10 px-5 py-3 text-sm text-cream placeholder:text-cream/50 focus:ring-2 focus:ring-accent"
                    />
                </div>

                <div v-if="categories.length" class="mt-4 flex flex-wrap gap-2">
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        type="button"
                        @click="pickCategory(cat)"
                        class="rounded-full px-4 py-1.5 text-xs uppercase tracking-[0.2em] border transition"
                        :class="selectedCategory === cat
                            ? 'bg-accent text-charcoal border-accent'
                            : 'border-cream/30 text-cream/70 hover:border-accent hover:text-accent'"
                    >
                        {{ cat }}
                    </button>
                </div>
            </div>
        </section>

        <section v-if="featured" class="py-12 bg-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <Link
                    :href="route('blog.show', featured.slug)"
                    class="group grid md:grid-cols-2 gap-10 items-stretch bg-white border border-primary/5 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition"
                >
                    <div class="aspect-[16/10] md:aspect-auto bg-primary/10 overflow-hidden">
                        <img loading="lazy" decoding="async" v-if="featured.cover_image" :src="storageUrl(featured.cover_image)" :alt="featured.title"
                             class="h-full w-full object-cover group-hover:scale-105 transition" />
                    </div>
                    <div class="p-8 sm:p-12 flex flex-col justify-center">
                        <p class="uppercase tracking-[0.3em] text-[10px] text-accent mb-3">{{ featured.category }} · Featured</p>
                        <h2 class="font-serif text-3xl text-primary group-hover:text-accent transition leading-tight">{{ featured.title }}</h2>
                        <p class="mt-4 text-charcoal/70 leading-relaxed">{{ featured.excerpt }}</p>
                        <div class="mt-6 text-xs text-charcoal/60 flex items-center gap-3">
                            <span v-if="featured.author">{{ featured.author.name }}</span>
                            <span v-if="featured.author">·</span>
                            <span>{{ formattedDate(featured.published_at) }}</span>
                            <span>·</span>
                            <span>{{ featured.read_time }} min read</span>
                        </div>
                    </div>
                </Link>
            </div>
        </section>

        <section class="py-12 bg-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-if="posts.data.length" class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <article v-for="post in posts.data" :key="post.id"
                             class="bg-white rounded-2xl overflow-hidden border border-primary/5 hover:shadow-md transition group">
                        <Link :href="route('blog.show', post.slug)" class="block">
                            <div class="aspect-[16/10] bg-primary/10 overflow-hidden">
                                <img loading="lazy" decoding="async" v-if="post.cover_image" :src="storageUrl(post.cover_image)" :alt="post.title"
                                     class="h-full w-full object-cover group-hover:scale-105 transition" />
                            </div>
                            <div class="p-6">
                                <p class="uppercase tracking-widest text-[10px] text-accent mb-2">{{ post.category }}</p>
                                <h3 class="font-serif text-lg text-primary group-hover:text-accent transition leading-snug">{{ post.title }}</h3>
                                <p class="mt-2 text-sm text-charcoal/70 line-clamp-3">{{ post.excerpt }}</p>
                                <div class="mt-4 text-xs text-charcoal/50 flex items-center gap-2">
                                    <span v-if="post.author">{{ post.author.name }}</span>
                                    <span v-if="post.author">·</span>
                                    <span>{{ formattedDate(post.published_at) }}</span>
                                    <span>·</span>
                                    <span>{{ post.read_time }} min read</span>
                                </div>
                            </div>
                        </Link>
                    </article>
                </div>
                <p v-else class="text-charcoal/60">No posts match your filters yet.</p>

                <nav v-if="posts.links?.length > 3" class="mt-12 flex flex-wrap justify-center gap-2">
                    <Link v-for="link in posts.links" :key="link.label"
                          :href="link.url || '#'"
                          v-html="link.label"
                          preserve-scroll
                          class="px-4 py-2 text-sm rounded-full border transition"
                          :class="link.active ? 'bg-primary text-cream border-primary' : (link.url ? 'border-primary/20 text-charcoal hover:border-primary' : 'border-charcoal/10 text-charcoal/30 pointer-events-none')"
                    />
                </nav>
            </div>
        </section>
    </PublicLayout>
</template>
