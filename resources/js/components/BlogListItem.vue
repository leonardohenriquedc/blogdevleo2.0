<template>
    <article>
        <RouterLink
            :to="{ name: 'blog', params: { name: slug } }"
            class="group block py-8 transition-colors duration-200 sm:py-10"
        >
            <p class="font-mono text-xs font-medium uppercase tracking-wider text-[#94a3b8]">
                {{ formattedDate || '—' }}
            </p>

            <h2
                class="mt-3 text-2xl font-bold leading-snug tracking-tight text-[#111827] transition-colors duration-200 group-hover:text-[#2563eb] sm:text-[1.75rem]"
            >
                {{ title }}
            </h2>

            <p
                v-if="description"
                class="mt-3 max-w-[65ch] text-base text-[#64748b]"
            >
                {{ description }}
            </p>

            <span
                class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-[#2563eb]"
            >
                Ler artigo
                <svg
                    class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </span>
        </RouterLink>
    </article>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { articleTitle, formatDate, toSlug } from '../services/format.js';

interface Article {
    name?: string;
    title?: string;
    description?: string;
    date?: string;
}

const props = defineProps<{
    article: Article;
}>();

const title = computed(() => articleTitle(props.article));
const description = computed(() => props.article?.description || '');
const formattedDate = computed(() => formatDate(props.article?.date));
const slug = computed(() => toSlug(props.article?.name ?? title.value));
</script>
