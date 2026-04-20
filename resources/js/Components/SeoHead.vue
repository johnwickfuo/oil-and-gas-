<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    title: { type: String, default: null },
    description: { type: String, default: null },
    image: { type: String, default: null },
    canonical: { type: String, default: null },
    type: { type: String, default: 'website' },
    structuredData: { type: [Array, Object], default: null },
    noindex: { type: Boolean, default: false },
});

const page = usePage();

const siteName = computed(() => page.props.site?.name || 'Blue Dine Cuisines');
const siteUrl = computed(() => page.props.site?.url || '');
const defaultImage = computed(() => page.props.site?.og_image || '');
const defaultDescription =
    'Blue Dine Cuisines — private chef, meal prep and small chops catering in Port Harcourt. Intimate dinners, healthy meal delivery and event catering by Chef Eureka.';

const fullTitle = computed(() =>
    props.title ? `${props.title} | ${siteName.value}` : `${siteName.value} — Private Chef & Meal Prep in Port Harcourt`,
);
const description = computed(() => props.description || defaultDescription);
const image = computed(() => {
    const value = props.image || defaultImage.value;
    if (!value) return value;
    if (/^https?:\/\//i.test(value)) return value;
    return siteUrl.value ? `${siteUrl.value.replace(/\/$/, '')}${value.startsWith('/') ? value : '/' + value}` : value;
});
const canonical = computed(() => {
    if (props.canonical) return props.canonical;
    if (typeof window !== 'undefined') return window.location.href;
    return siteUrl.value;
});

const structuredJson = computed(() => {
    if (!props.structuredData) return null;
    return JSON.stringify(props.structuredData);
});
</script>

<template>
    <Head :title="fullTitle">
        <meta head-key="description" name="description" :content="description" />
        <meta head-key="keywords" name="keywords" content="private chef Port Harcourt, meal prep Port Harcourt, small chops catering Port Harcourt, healthy meal delivery Port Harcourt" />
        <link head-key="canonical" rel="canonical" :href="canonical" />
        <meta v-if="noindex" head-key="robots" name="robots" content="noindex,nofollow" />

        <meta head-key="og:site_name" property="og:site_name" :content="siteName" />
        <meta head-key="og:title" property="og:title" :content="fullTitle" />
        <meta head-key="og:description" property="og:description" :content="description" />
        <meta head-key="og:type" property="og:type" :content="type" />
        <meta head-key="og:url" property="og:url" :content="canonical" />
        <meta v-if="image" head-key="og:image" property="og:image" :content="image" />

        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
        <meta head-key="twitter:title" name="twitter:title" :content="fullTitle" />
        <meta head-key="twitter:description" name="twitter:description" :content="description" />
        <meta v-if="image" head-key="twitter:image" name="twitter:image" :content="image" />

        <component
            :is="'script'"
            v-if="structuredJson"
            head-key="ld-json"
            type="application/ld+json"
            v-html="structuredJson"
        />
    </Head>
</template>
