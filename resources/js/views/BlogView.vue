<template>
    <main class="mx-auto w-full max-w-3xl px-5 sm:px-6">
        <!-- Back link -->
        <RouterLink
            to="/"
            class="mt-10 inline-flex items-center gap-1.5 text-sm font-medium text-[#64748b] transition-colors hover:text-[#2563eb]"
        >
            <svg
                class="h-4 w-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>
            Voltar para todos os artigos
        </RouterLink>

        <Loading v-if="loading" message="Carregando artigo..." />

        <!-- Not found -->
        <div v-else-if="notFound" class="py-20 text-center">
            <p class="text-xl font-bold text-[#111827]">
                Artigo não encontrado.
            </p>
            <RouterLink
                to="/"
                class="mt-6 inline-flex items-center gap-1.5 text-sm font-medium text-[#2563eb] hover:underline"
            >
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="M19 12H5" />
                    <path d="m12 19-7-7 7-7" />
                </svg>
                Voltar para todos os artigos
            </RouterLink>
        </div>

        <!-- Error -->
        <div v-else-if="error" class="py-20 text-center">
            <p class="text-base font-medium text-[#111827]">
                Não foi possível carregar os artigos.
            </p>
            <p class="mt-1 text-sm text-[#64748b]">Tente novamente.</p>
        </div>

        <!-- Article -->
        <template v-else-if="content">
            <article>
                <header class="pt-12 sm:pt-16">
                    <time
                        v-if="date"
                        class="font-mono text-xs font-medium uppercase tracking-wider text-[#94a3b8]"
                    >
                        {{ date }}
                    </time>

                    <h1
                        class="mt-3 text-3xl font-bold leading-tight tracking-tight text-[#111827] sm:text-4xl"
                    >
                        {{ title }}
                    </h1>

                    <p class="mt-4 text-sm text-[#64748b]">
                        {{ readingTime }}
                    </p>
                </header>

                <hr class="mt-8 border-t border-slate-200" />

                <div class="mt-10">
                    <CodeBlock :html="content" />
                </div>
            </article>

            <div class="mt-14 border-t border-slate-200 pt-8 pb-4">
                <RouterLink
                    to="/"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-[#64748b] transition-colors hover:text-[#2563eb]"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M19 12H5" />
                        <path d="m12 19-7-7 7-7" />
                    </svg>
                    Voltar para todos os artigos
                </RouterLink>
            </div>
        </template>
    </main>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { fetchArticle, fetchArticles } from '../services/api.js';
import { articleTitle, formatDate, toSlug } from '../services/format.js';
import CodeBlock from '../components/CodeBlock.vue';
import Loading from '../components/Loading.vue';

interface ArticleMeta {
    name?: string;
    title?: string;
    date?: string;
}

const props = defineProps<{
    name: string;
}>();

const loading = ref(true);
const error = ref(false);
const notFound = ref(false);

const meta = ref<ArticleMeta>({});
const content = ref('');

const slug = computed(() => toSlug(props.name));
const title = computed(() => articleTitle(meta.value) || props.name);
const date = computed(() => formatDate(meta.value?.date));
const readingTime = computed(() => {
    const words = (content.value || '')
        .replace(/<[^>]+>/g, ' ')
        .split(/\s+/)
        .filter(Boolean).length;

    const minutes = Math.max(1, Math.ceil(words / 200));

    return minutes === 1 ? '1 min de leitura' : `${minutes} min de leitura`;
});

async function load() {
    loading.value = true;
    error.value = false;
    notFound.value = false;
    content.value = '';

    try {
        const article = await fetchArticle(slug.value);
        content.value = article.content || '';

        // The /get/{name} endpoint only returns the content. Pull the
        // article's metadata (title/date) from the listing endpoint.
        try {
            const articles = await fetchArticles();
            const match = articles.find((item) => toSlug(item.name) === slug.value);
            meta.value = match ?? {};
        } catch {
            meta.value = {};
        }
    } catch (err) {
        if ((err as { status?: number }).status === 404) {
            notFound.value = true;
        } else {
            error.value = true;
        }
    } finally {
        loading.value = false;
    }
}

onMounted(load);
watch(() => slug.value, load);
</script>
