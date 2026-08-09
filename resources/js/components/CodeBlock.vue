<template>
    <div ref="host" class="article-content" v-html="html"></div>
</template>

<script setup lang="ts">
import { nextTick, onMounted, ref, watch } from 'vue';
import type { Ref } from 'vue';

const props = withDefaults(
    defineProps<{
        html?: string;
    }>(),
    {
        html: '',
    },
);

const host: Ref<HTMLElement | null> = ref(null);

/**
 * Enhances every <pre> block rendered by the backend with a copy button.
 */
function enhanceCodeBlocks() {
    if (!host.value) {
        return;
    }

    host.value.querySelectorAll('pre').forEach((pre) => {
        if (pre.querySelector('.code-copy-btn')) {
            return;
        }

        const code = pre.querySelector('code');

        if (!code) {
            return;
        }

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'code-copy-btn';
        button.setAttribute('aria-label', 'Copiar código');
        button.textContent = 'copiar';

        button.addEventListener('click', async () => {
            await copyText(code.innerText);
            button.textContent = 'copiado!';
            button.classList.add('is-copied');

            setTimeout(() => {
                button.textContent = 'copiar';
                button.classList.remove('is-copied');
            }, 1800);
        });

        pre.appendChild(button);
    });
}

async function copyText(text: string) {
    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(text);

            return;
        }
    } catch {
        // fall through to the legacy approach
    }

    // Legacy fallback for non-HTTPS contexts
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'absolute';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
}

async function hydrate() {
    await nextTick();
    enhanceCodeBlocks();
}

onMounted(hydrate);
watch(() => props.html, hydrate);
</script>
