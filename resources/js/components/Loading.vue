<template>
    <div
        class="flex flex-col items-center justify-center gap-3 py-16 text-center"
        role="status"
    >
        <svg
            class="h-6 w-6 animate-spin text-[#2563eb]"
            viewBox="0 0 24 24"
            fill="none"
            aria-hidden="true"
        >
            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            />
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"
            />
        </svg>
        <p class="text-sm text-[#64748b]">{{ message }}</p>
    </div>
</template>

<script setup lang="ts">
withDefaults(
    defineProps<{
        message?: string;
    }>(),
    {
        message: 'Carregando...',
    },
);
</script>
