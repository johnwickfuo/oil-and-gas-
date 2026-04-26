<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';

const props = defineProps({
    post: { type: Object, required: true },
    related: { type: Array, default: () => [] },
});

const page = usePage();
const absoluteImage = computed(() => {
    if (!props.post.cover_image) return null;
    const siteUrl = page.props.site?.url || '';
    return `${siteUrl}/storage/${props.post.cover_image}`;
});
const postUrl = computed(() => `${page.props.site?.url || ''}/blog/${props.post.slug}`);
const structuredData = computed(() => [
    {
        '@context': 'https://schema.org',
        '@type': 'Article',
        headline: props.post.title,
        description: props.post.meta_description || props.post.excerpt,
        image: absoluteImage.value ? [absoluteImage.value] : undefined,
        author: props.post.author
            ? { '@type': 'Person', name: props.post.author.name }
            : undefined,
        publisher: {
            '@type': 'Organization',
            name: page.props.site?.name || 'Blue Dine Cuisines',
            logo: page.props.site?.og_image
                ? { '@type': 'ImageObject', url: page.props.site.og_image }
                : undefined,
        },
        datePublished: props.post.published_at,
        dateModified: props.post.published_at,
        mainEntityOfPage: { '@type': 'WebPage', '@id': postUrl.value },
        keywords: (props.post.tags || []).join(', ') || undefined,
    },
    {
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        itemListElement: [
            { '@type': 'ListItem', position: 1, name: 'Home', item: page.props.site?.url || '/' },
            { '@type': 'ListItem', position: 2, name: 'Journal', item: `${page.props.site?.url || ''}/blog` },
            { '@type': 'ListItem', position: 3, name: props.post.title, item: postUrl.value },
        ],
    },
]);

const storageUrl = (path) => (path ? `/storage/${path}` : null);
const formattedDate = (iso) => iso ? new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }) : '';

const pageUrl = computed(() => (typeof window !== 'undefined' ? window.location.href : ''));
const shareTitle = computed(() => `${props.post.title} — Blue Dine Cuisines`);

const twitterHref = computed(() => `https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl.value)}&text=${encodeURIComponent(shareTitle.value)}`);
const facebookHref = computed(() => `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl.value)}`);
const whatsappHref = computed(() => `https://wa.me/?text=${encodeURIComponent(shareTitle.value + ' ' + pageUrl.value)}`);

const copied = ref(false);
async function copyLink() {
    try {
        await navigator.clipboard.writeText(pageUrl.value);
        copied.value = true;
        setTimeout(() => (copied.value = false), 2000);
    } catch (e) {
        // noop
    }
}
</script>

<template>
    <SeoHead
        :title="post.meta_title || post.title"
        :description="post.meta_description || post.excerpt"
        :image="absoluteImage"
        type="article"
        :structured-data="structuredData"
    />

    <PublicLayout>
        <article>
            <section class="relative bg-primary text-cream">
                <div class="absolute inset-0 opacity-30">
                    <img v-if="post.cover_image" :src="storageUrl(post.cover_image)" :alt="post.title"
                         class="h-full w-full object-cover" />
                </div>
                <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-24">
                    <Link :href="route('blog.index')" class="text-xs uppercase tracking-[0.3em] text-accent hover:text-accent/80">
                        &larr; All articles
                    </Link>
                    <p class="mt-6 uppercase tracking-[0.3em] text-xs text-accent">{{ post.category }}</p>
                    <h1 class="mt-4 font-serif text-4xl sm:text-5xl leading-tight">{{ post.title }}</h1>
                    <div class="mt-8 flex flex-wrap items-center gap-3 text-sm text-cream/80">
                        <span v-if="post.author" class="font-semibold text-cream">{{ post.author.name }}</span>
                        <span v-if="post.author">·</span>
                        <span>{{ formattedDate(post.published_at) }}</span>
                        <span>·</span>
                        <span>{{ post.read_time }} min read</span>
                    </div>
                </div>
            </section>

            <section class="py-16 bg-cream">
                <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                    <div v-if="post.cover_image" class="rounded-2xl overflow-hidden mb-10 -mt-28 shadow-lg bg-cream relative z-10">
                        <img :src="storageUrl(post.cover_image)" :alt="post.title" class="w-full h-auto" />
                    </div>

                    <div
                        class="prose prose-neutral max-w-none prose-headings:font-serif prose-headings:text-primary prose-p:text-charcoal/85 prose-a:text-primary prose-a:no-underline hover:prose-a:underline"
                        v-html="post.body"
                    ></div>

                    <div v-if="post.tags?.length" class="mt-10 flex flex-wrap gap-2">
                        <span v-for="tag in post.tags" :key="tag" class="text-xs uppercase tracking-[0.2em] text-charcoal/60 border border-primary/20 rounded-full px-3 py-1">
                            {{ tag }}
                        </span>
                    </div>

                    <div class="mt-12 pt-8 border-t border-primary/10">
                        <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Share</p>
                        <div class="flex flex-wrap gap-3">
                            <a :href="twitterHref" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 text-sm text-charcoal hover:border-primary hover:text-primary transition">
                                Twitter
                            </a>
                            <a :href="facebookHref" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 text-sm text-charcoal hover:border-primary hover:text-primary transition">
                                Facebook
                            </a>
                            <a :href="whatsappHref" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 text-sm text-charcoal hover:border-primary hover:text-primary transition">
                                WhatsApp
                            </a>
                            <button type="button" @click="copyLink"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 text-sm text-charcoal hover:border-primary hover:text-primary transition">
                                {{ copied ? 'Copied!' : 'Copy link' }}
                            </button>
                        </div>
                    </div>

                    <div v-if="post.author" class="mt-12 bg-white border border-primary/5 rounded-2xl p-6 sm:p-8 flex gap-5 items-start">
                        <div class="flex-none h-14 w-14 rounded-full bg-accent/20 flex items-center justify-center font-serif text-xl text-primary">
                            {{ post.author.name.charAt(0) }}
                        </div>
                        <div>
                            <p class="uppercase tracking-widest text-[10px] text-accent mb-1">Written by</p>
                            <h3 class="font-serif text-lg text-primary">{{ post.author.name }}</h3>
                            <p class="mt-2 text-sm text-charcoal/70">
                                Part of the Blue Dine kitchen team, sharing field notes from the pass.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section v-if="related.length" class="py-16 bg-white border-t border-primary/5">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <p class="uppercase tracking-[0.3em] text-xs text-accent mb-3">Keep reading</p>
                    <h2 class="font-serif text-3xl text-primary mb-10">More from the kitchen</h2>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <Link v-for="r in related" :key="r.id"
                              :href="route('blog.show', r.slug)"
                              class="group bg-cream rounded-2xl overflow-hidden border border-primary/5 hover:shadow-md transition">
                            <div class="aspect-[16/10] bg-primary/10 overflow-hidden">
                                <img v-if="r.cover_image" :src="storageUrl(r.cover_image)" :alt="r.title"
                                     class="h-full w-full object-cover group-hover:scale-105 transition" />
                            </div>
                            <div class="p-5">
                                <p class="uppercase tracking-widest text-[10px] text-accent mb-2">{{ r.category }}</p>
                                <h3 class="font-serif text-lg text-primary leading-snug group-hover:text-accent transition">{{ r.title }}</h3>
                                <p class="mt-2 text-xs text-charcoal/60">{{ formattedDate(r.published_at) }} · {{ r.read_time }} min</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </section>
        </article>
    </PublicLayout>
</template>
