<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const mobileOpen = ref(false);

const navLinks = [
    { label: 'Home', href: '/' },
    { label: 'About', href: '/about' },
    { label: 'Services', href: '/services' },
    { label: 'Menu', href: '/menu' },
    { label: 'Gallery', href: '/gallery' },
    { label: 'Blog', href: '/blog' },
    { label: 'Recipes', href: '/recipes' },
];

const footerExtraLinks = [
    { label: 'Resources', href: '/resources' },
    { label: 'Academy', href: '/academy' },
    { label: 'Contact', href: '/contact' },
];

const whatsappNumber = import.meta.env.VITE_WHATSAPP_NUMBER || '';
const whatsappHref = `https://wa.me/${whatsappNumber}`;

const page = usePage();
const newsletterForm = useForm({
    email: '',
    source: 'footer',
    website: '',
});

const newsletterStatus = computed(() => page.props.flash?.status || '');

function subscribeNewsletter() {
    newsletterForm.post(route('newsletter.subscribe'), {
        preserveScroll: true,
        onSuccess: () => newsletterForm.reset(),
    });
}

const closeMobile = () => (mobileOpen.value = false);

const handleEsc = (e) => {
    if (e.key === 'Escape') closeMobile();
};

onMounted(() => window.addEventListener('keydown', handleEsc));
onBeforeUnmount(() => window.removeEventListener('keydown', handleEsc));
</script>

<template>
    <div class="min-h-screen flex flex-col bg-cream text-charcoal">
        <header class="sticky top-0 z-40 bg-cream/95 backdrop-blur border-b border-primary/10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">
                    <Link
                        href="/"
                        class="font-serif text-2xl md:text-3xl font-semibold text-primary tracking-wide"
                    >
                        Blue Dine Cuisines
                    </Link>

                    <nav class="hidden md:flex items-center gap-8">
                        <Link
                            v-for="link in navLinks"
                            :key="link.href"
                            :href="link.href"
                            class="text-sm font-medium text-charcoal hover:text-primary transition"
                        >
                            {{ link.label }}
                        </Link>
                        <Link
                            :href="route('booking.create')"
                            class="inline-flex items-center px-5 py-2.5 rounded-full bg-accent text-charcoal font-semibold text-sm shadow-sm hover:bg-accent/90 transition"
                        >
                            Book Now
                        </Link>
                    </nav>

                    <button
                        type="button"
                        class="md:hidden inline-flex items-center justify-center p-2 rounded text-primary hover:bg-primary/5"
                        aria-label="Open menu"
                        @click="mobileOpen = true"
                    >
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="mobileOpen"
                class="fixed inset-0 z-50 bg-charcoal/50 md:hidden"
                @click="closeMobile"
            ></div>
        </transition>

        <transition
            enter-active-class="transition transform duration-300 ease-out"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <aside
                v-if="mobileOpen"
                class="fixed top-0 right-0 z-50 h-full w-72 bg-cream shadow-xl md:hidden flex flex-col"
            >
                <div class="flex items-center justify-between px-5 h-20 border-b border-primary/10">
                    <span class="font-serif text-xl text-primary">Menu</span>
                    <button
                        type="button"
                        class="p-2 rounded text-primary hover:bg-primary/5"
                        aria-label="Close menu"
                        @click="closeMobile"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="flex flex-col px-5 py-6 gap-4">
                    <Link
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="text-base font-medium text-charcoal hover:text-primary transition"
                        @click="closeMobile"
                    >
                        {{ link.label }}
                    </Link>
                    <Link
                        :href="route('booking.create')"
                        class="mt-4 inline-flex items-center justify-center px-5 py-3 rounded-full bg-accent text-charcoal font-semibold text-sm hover:bg-accent/90 transition"
                        @click="closeMobile"
                    >
                        Book Now
                    </Link>
                </nav>
            </aside>
        </transition>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="bg-primary text-cream">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid gap-10 md:grid-cols-4">
                    <div>
                        <h3 class="font-serif text-xl text-accent mb-4">Blue Dine Cuisines</h3>
                        <p class="text-sm text-cream/80 leading-relaxed">
                            Private chef and meal prep service based in Port Harcourt, crafting
                            intimate dining experiences and wholesome weekly menus.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-serif text-lg text-accent mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-sm text-cream/80">
                            <li v-for="link in navLinks" :key="link.href">
                                <Link :href="link.href" class="hover:text-accent transition">
                                    {{ link.label }}
                                </Link>
                            </li>
                            <li v-for="link in footerExtraLinks" :key="link.href">
                                <Link :href="link.href" class="hover:text-accent transition">
                                    {{ link.label }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-serif text-lg text-accent mb-4">Contact</h4>
                        <ul class="space-y-2 text-sm text-cream/80">
                            <li>Port Harcourt, Nigeria</li>
                            <li>hello@bluedinecuisines.com</li>
                            <li>+234 000 000 0000</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-serif text-lg text-accent mb-4">Newsletter</h4>
                        <p class="text-sm text-cream/80 mb-4">
                            Seasonal menus and event announcements in your inbox.
                        </p>
                        <p v-if="newsletterStatus" class="mb-3 rounded-lg bg-accent/15 border border-accent/30 px-3 py-2 text-xs text-cream">
                            {{ newsletterStatus }}
                        </p>
                        <form class="flex flex-col gap-3" @submit.prevent="subscribeNewsletter">
                            <input
                                v-model="newsletterForm.website"
                                type="text"
                                class="sr-only"
                                tabindex="-1"
                                autocomplete="off"
                                aria-hidden="true"
                            />
                            <input
                                v-model="newsletterForm.email"
                                type="email"
                                required
                                placeholder="you@example.com"
                                class="w-full rounded-full border-0 bg-cream/10 px-4 py-2.5 text-sm text-cream placeholder:text-cream/50 focus:ring-2 focus:ring-accent"
                            />
                            <p v-if="newsletterForm.errors.email" class="text-xs text-red-300">
                                {{ newsletterForm.errors.email }}
                            </p>
                            <button
                                type="submit"
                                :disabled="newsletterForm.processing"
                                class="rounded-full bg-accent text-charcoal font-semibold text-sm px-4 py-2.5 hover:bg-accent/90 transition disabled:opacity-60"
                            >
                                {{ newsletterForm.processing ? 'Subscribing…' : 'Subscribe' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-cream/10 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-5">
                        <a href="#" aria-label="Instagram" class="text-cream/80 hover:text-accent transition">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.849.07 1.366.062 2.633.336 3.608 1.311.975.975 1.249 2.242 1.311 3.608.058 1.265.07 1.645.07 4.849s-.012 3.584-.07 4.849c-.062 1.366-.336 2.633-1.311 3.608-.975.975-2.242 1.249-3.608 1.311-1.265.058-1.645.07-4.849.07s-3.584-.012-4.849-.07c-1.366-.062-2.633-.336-3.608-1.311-.975-.975-1.249-2.242-1.311-3.608C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.849c.062-1.366.336-2.633 1.311-3.608.975-.975 2.242-1.249 3.608-1.311C8.416 2.175 8.796 2.163 12 2.163zm0 1.838c-3.152 0-3.522.012-4.763.068-.936.043-1.444.199-1.782.332a2.97 2.97 0 0 0-1.076.7 2.97 2.97 0 0 0-.7 1.076c-.133.338-.289.846-.332 1.782-.056 1.241-.068 1.611-.068 4.763s.012 3.522.068 4.763c.043.936.199 1.444.332 1.782.155.398.34.682.7 1.076.394.36.678.545 1.076.7.338.133.846.289 1.782.332 1.241.056 1.611.068 4.763.068s3.522-.012 4.763-.068c.936-.043 1.444-.199 1.782-.332a2.97 2.97 0 0 0 1.076-.7 2.97 2.97 0 0 0 .7-1.076c.133-.338.289-.846.332-1.782.056-1.241.068-1.611.068-4.763s-.012-3.522-.068-4.763c-.043-.936-.199-1.444-.332-1.782a2.97 2.97 0 0 0-.7-1.076 2.97 2.97 0 0 0-1.076-.7c-.338-.133-.846-.289-1.782-.332C15.522 4.013 15.152 4 12 4zm0 3.838a4.162 4.162 0 1 0 0 8.324 4.162 4.162 0 0 0 0-8.324zm0 6.87a2.708 2.708 0 1 1 0-5.416 2.708 2.708 0 0 1 0 5.416zm5.294-7.06a.973.973 0 1 1-1.946 0 .973.973 0 0 1 1.946 0z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Facebook" class="text-cream/80 hover:text-accent transition">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.51 1.49-3.9 3.78-3.9 1.1 0 2.25.2 2.25.2v2.47h-1.27c-1.25 0-1.64.78-1.64 1.57V12h2.79l-.45 2.89h-2.34v6.99A10 10 0 0 0 22 12z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="TikTok" class="text-cream/80 hover:text-accent transition">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43V9.41a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.84 4.84 0 0 1-1.84-.84z" />
                            </svg>
                        </a>
                    </div>
                    <p class="text-xs text-cream/60">
                        &copy; {{ new Date().getFullYear() }} Blue Dine Cuisines. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

        <a
            :href="whatsappHref"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Chat on WhatsApp"
            class="fixed bottom-6 right-6 z-40 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg hover:scale-110 hover:bg-[#20b858] transition-transform"
        >
            <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.52 3.48A11.78 11.78 0 0 0 12.05 0C5.5 0 .2 5.3.2 11.85c0 2.09.55 4.13 1.6 5.93L0 24l6.38-1.67a11.84 11.84 0 0 0 5.67 1.45h.01c6.54 0 11.85-5.3 11.85-11.85a11.77 11.77 0 0 0-3.39-8.45zM12.06 21.8h-.01a9.85 9.85 0 0 1-5.02-1.38l-.36-.21-3.79.99 1.01-3.69-.23-.38a9.85 9.85 0 0 1-1.51-5.28c0-5.45 4.43-9.88 9.9-9.88 2.64 0 5.12 1.03 6.99 2.9a9.82 9.82 0 0 1 2.89 6.98c0 5.45-4.43 9.89-9.89 9.89zm5.43-7.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.64-2.05-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.1 3.2 5.08 4.49.71.31 1.27.49 1.7.62.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35z"/>
            </svg>
        </a>
    </div>
</template>
