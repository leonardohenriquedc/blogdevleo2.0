<template>
    <main class="mx-auto w-full max-w-5xl px-5 sm:px-8">
        <!-- Hero -->
        <section class="flex flex-col items-center py-20 text-center sm:py-28">
            <h1
                class="text-4xl font-bold tracking-tight text-[#111827] sm:text-5xl"
            >
                Desenvolvimento &amp; Tecnologia
            </h1>
            <p
                class="mt-5 max-w-lg text-lg leading-relaxed text-[#64748b]"
            >
                Artigos sobre programação, projetos e experiências de
                desenvolvimento.
            </p>
        </section>

        <!-- Article list -->
        <section aria-labelledby="artigos-titulo">
            <h2
                id="artigos-titulo"
                class="sr-only"
            >
                Artigos
            </h2>

            <Loading v-if="loading" message="Carregando artigos..." />

            <div
                v-else-if="error"
                class="py-16 text-center"
            >
                <p class="text-base font-medium text-[#111827]">
                    Não foi possível carregar os artigos.
                </p>
                <p class="mt-1 text-sm text-[#64748b]">Tente novamente.</p>
                <button
                    type="button"
                    class="mt-6 rounded-md bg-[#2563eb] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#1d4ed8]"
                    @click="loadArticles"
                >
                    Tentar novamente
                </button>
            </div>

            <div v-else-if="articles.length === 0" class="py-16 text-center">
                <p class="text-base text-[#64748b]">Nenhum artigo por enquanto.</p>
            </div>

            <div v-else class="divide-y divide-slate-200">
                <BlogListItem
                    v-for="article in articles"
                    :key="article.name || article.title"
                    :article="article"
                />
            </div>
        </section>
    </main>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import BlogListItem from '../components/BlogListItem.vue';
import Loading from '../components/Loading.vue';
import { fetchArticles } from '../services/api.js';

interface Article {
    name?: string;
    title?: string;
    description?: string;
    date?: string;
}

const loading = ref(true);
const error = ref(false);
const articles = ref<Article[]>([]);

async function loadArticles() {
    loading.value = true;
    error.value = false;

    try {
        articles.value = await fetchArticles();
    } catch {
        articles.value = [];
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(loadArticles);
</script>
