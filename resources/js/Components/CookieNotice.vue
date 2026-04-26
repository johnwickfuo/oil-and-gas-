<script setup>
import { ref, onMounted } from 'vue';

const STORAGE_KEY = 'bluedine_cookie_ack';
const visible = ref(false);

onMounted(() => {
    try {
        if (!localStorage.getItem(STORAGE_KEY)) {
            visible.value = true;
        }
    } catch (e) {
        visible.value = false;
    }
});

function accept() {
    try {
        localStorage.setItem(STORAGE_KEY, '1');
    } catch (e) {
        // ignore storage errors
    }
    visible.value = false;
}
</script>

<template>
    <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            v-if="visible"
            role="region"
            aria-label="Cookie notice"
            class="fixed bottom-4 left-4 right-4 sm:left-6 sm:right-6 md:left-auto md:right-6 md:max-w-lg z-40 rounded-2xl bg-charcoal text-cream shadow-2xl px-5 py-4"
        >
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <p class="text-sm leading-relaxed flex-1">
                    We use cookies to improve your experience on Blue Dine Cuisines.
                    <a href="/privacy" class="underline hover:text-accent focus-visible:text-accent">Learn more</a>.
                </p>
                <button
                    type="button"
                    class="shrink-0 inline-flex items-center justify-center rounded-full bg-accent text-charcoal font-semibold text-sm px-4 py-2 hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent transition"
                    @click="accept"
                >
                    Got it
                </button>
            </div>
        </div>
    </transition>
</template>
