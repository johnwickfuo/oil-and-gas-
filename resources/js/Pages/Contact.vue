<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SeoHead from '@/Components/SeoHead.vue';
import WhatsAppButton from '@/Components/WhatsAppButton.vue';

const props = defineProps({
    details: { type: Object, default: () => ({}) },
});

const page = usePage();
const flashStatus = computed(() => page.props.flash?.status);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
    website: '',
});

const submit = () => {
    form.post(route('contact.submit'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <SeoHead
        title="Contact"
        description="Reach Blue Dine Cuisines — private chef, meal prep and small chops catering in Port Harcourt. WhatsApp, phone and email."
    />

    <PublicLayout>
        <section class="bg-primary text-cream">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-20">
                <p class="uppercase tracking-[0.3em] text-xs text-accent mb-6">Say hello</p>
                <h1 class="font-serif text-4xl sm:text-5xl">Get in touch</h1>
                <p class="mt-6 text-cream/80 max-w-2xl">
                    Share a few details about your event, dates or dietary needs and we'll reply within one
                    business day.
                </p>
            </div>
        </section>

        <section class="py-16 bg-cream">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-5 gap-10">
                <div class="lg:col-span-3 bg-white border border-primary/5 rounded-2xl p-8 shadow-sm">
                    <div
                        v-if="flashStatus"
                        class="mb-6 rounded-xl bg-primary/5 text-primary px-4 py-3 text-sm"
                    >
                        {{ flashStatus }}
                    </div>
                    <form class="grid gap-5" @submit.prevent="submit">
                        <div class="sr-only" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" v-model="form.website" type="text" tabindex="-1" autocomplete="off" />
                        </div>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Name</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"
                                />
                                <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label for="email" class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Email</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"
                                />
                                <p v-if="form.errors.email" class="text-xs text-red-600 mt-1">{{ form.errors.email }}</p>
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Phone (optional)</label>
                            <input
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"
                            />
                            <p v-if="form.errors.phone" class="text-xs text-red-600 mt-1">{{ form.errors.phone }}</p>
                        </div>
                        <div>
                            <label for="message" class="block text-xs uppercase tracking-widest text-charcoal/60 mb-2">Message</label>
                            <textarea
                                id="message"
                                v-model="form.message"
                                required
                                rows="6"
                                class="w-full rounded-xl border border-primary/10 bg-cream/50 px-4 py-3 text-sm focus:border-primary focus:ring-primary"
                            ></textarea>
                            <p v-if="form.errors.message" class="text-xs text-red-600 mt-1">{{ form.errors.message }}</p>
                        </div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-primary text-cream font-semibold text-sm hover:bg-primary/90 transition disabled:opacity-60"
                        >
                            <span v-if="form.processing">Sending…</span>
                            <span v-else>Send message</span>
                        </button>
                    </form>
                </div>

                <aside class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-primary/5 rounded-2xl p-8 shadow-sm">
                        <h3 class="font-serif text-xl text-primary mb-5">Kitchen details</h3>
                        <dl class="space-y-4 text-sm text-charcoal/80">
                            <div v-if="details.business_address">
                                <dt class="uppercase tracking-widest text-[10px] text-accent">Based in</dt>
                                <dd>{{ details.business_address }}</dd>
                            </div>
                            <div v-if="details.service_area">
                                <dt class="uppercase tracking-widest text-[10px] text-accent">Service area</dt>
                                <dd>{{ details.service_area }}</dd>
                            </div>
                            <div v-if="details.email">
                                <dt class="uppercase tracking-widest text-[10px] text-accent">Email</dt>
                                <dd><a :href="`mailto:${details.email}`" class="hover:text-primary">{{ details.email }}</a></dd>
                            </div>
                            <div v-if="details.phone">
                                <dt class="uppercase tracking-widest text-[10px] text-accent">Phone</dt>
                                <dd>{{ details.phone }}</dd>
                            </div>
                        </dl>
                        <div v-if="details.whatsapp_number" class="mt-6">
                            <WhatsAppButton
                                :number="details.whatsapp_number"
                                message="Hi Blue Dine, I'd like to make an enquiry."
                            />
                        </div>
                    </div>

                    <div class="rounded-2xl overflow-hidden border border-primary/5 shadow-sm bg-white">
                        <iframe
                            title="Port Harcourt map"
                            src="https://www.google.com/maps?q=Port+Harcourt,+Rivers,+Nigeria&output=embed"
                            class="w-full h-72 border-0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </aside>
            </div>
        </section>
    </PublicLayout>
</template>
