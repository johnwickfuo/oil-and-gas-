<script setup>
import { computed } from 'vue';

const props = defineProps({
    number: { type: String, default: '' },
    message: { type: String, default: '' },
    label: { type: String, default: 'Chat on WhatsApp' },
    variant: { type: String, default: 'solid' },
});

const resolvedNumber = computed(() => (props.number || import.meta.env.VITE_WHATSAPP_NUMBER || '').replace(/\D/g, ''));

const href = computed(() => {
    const base = `https://wa.me/${resolvedNumber.value}`;
    return props.message ? `${base}?text=${encodeURIComponent(props.message)}` : base;
});

const classes = computed(() => {
    if (props.variant === 'outline') {
        return 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full border border-[#25D366] text-[#25D366] font-semibold text-sm hover:bg-[#25D366] hover:text-white transition';
    }
    return 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-[#25D366] text-white font-semibold text-sm shadow-sm hover:bg-[#20b858] transition';
});
</script>

<template>
    <a :href="href" :class="classes" target="_blank" rel="noopener noreferrer">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M20.52 3.48A11.78 11.78 0 0 0 12.05 0C5.5 0 .2 5.3.2 11.85c0 2.09.55 4.13 1.6 5.93L0 24l6.38-1.67a11.84 11.84 0 0 0 5.67 1.45h.01c6.54 0 11.85-5.3 11.85-11.85a11.77 11.77 0 0 0-3.39-8.45zM12.06 21.8a9.85 9.85 0 0 1-5.02-1.38l-.36-.21-3.79.99 1.01-3.69-.23-.38a9.85 9.85 0 0 1-1.51-5.28c0-5.45 4.43-9.88 9.9-9.88 2.64 0 5.12 1.03 6.99 2.9a9.82 9.82 0 0 1 2.89 6.98c0 5.45-4.43 9.89-9.89 9.89zm5.43-7.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.64-2.05-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.1 3.2 5.08 4.49.71.31 1.27.49 1.7.62.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35z" />
        </svg>
        <span><slot>{{ label }}</slot></span>
    </a>
</template>
