<template>
    <header class="border-b border-slate-200 bg-white">
        <div
            class="mx-auto flex h-16 max-w-5xl items-center justify-between px-5 sm:px-8"
        >
            <RouterLink
                to="/"
                class="group flex items-center gap-2 font-mono text-[15px] font-semibold text-[#111827]"
            >
                <span
                    class="flex h-7 w-7 items-center justify-center rounded-md bg-[#2563eb] font-mono text-sm font-bold text-white transition-transform group-hover:-translate-y-px"
                    aria-hidden="true"
                >
                    &lt;/&gt;
                </span>
                blogdevleo.com
            </RouterLink>

            <nav class="flex items-center gap-1 sm:gap-2" aria-label="Navegação">
                <RouterLink
                    to="/"
                    class="rounded-md px-3 py-1.5 text-sm font-medium text-[#64748b] transition-colors hover:text-[#2563eb]"
                    exact-active-class="text-[#2563eb]"
                >
                    Início
                </RouterLink>
                <a
                    href="#contato"
                    class="rounded-md px-3 py-1.5 text-sm font-medium text-[#64748b] transition-colors hover:text-[#2563eb]"
                >
                    Sobre
                </a>
            </nav>
        </div>
    </header>
</template>
