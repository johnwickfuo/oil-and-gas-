<script setup>
import { computed } from 'vue';

const props = defineProps({
    src: { type: String, default: null },
    alt: { type: String, required: true },
    priority: { type: Boolean, default: false },
    sizes: { type: String, default: '(min-width: 1024px) 1024px, 100vw' },
    imgClass: { type: String, default: '' },
    width: { type: [Number, String], default: null },
    height: { type: [Number, String], default: null },
});

const storageUrl = (path) => (path ? `/storage/${path}` : null);

const fullSrc = computed(() => storageUrl(props.src));
const loading = computed(() => (props.priority ? 'eager' : 'lazy'));
const decoding = computed(() => (props.priority ? 'sync' : 'async'));
const fetchPriority = computed(() => (props.priority ? 'high' : 'auto'));
</script>

<template>
    <img
        v-if="fullSrc"
        :src="fullSrc"
        :alt="alt"
        :loading="loading"
        :decoding="decoding"
        :fetchpriority="fetchPriority"
        :width="width"
        :height="height"
        :class="imgClass"
    />
</template>
