<template>
    <footer id="contato" class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-5xl px-5 py-14 sm:px-8">
            <div
                class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3"
            >
                <!-- Brand -->
                <div>
                    <RouterLink to="/" class="inline-flex items-center gap-2">
                        <span
                            class="flex h-7 w-7 items-center justify-center rounded-md bg-[#2563eb] font-mono text-sm font-bold text-white"
                            aria-hidden="true"
                        >
                            &lt;/&gt;
                        </span>
                        <span
                            class="font-mono text-base font-semibold text-[#111827]"
                        >
                            BlogDevLeo
                        </span>
                    </RouterLink>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-[#64748b]">
                        Conteúdo sobre desenvolvimento, tecnologia e boas
                        práticas.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-[#111827]">
                        Links
                    </h3>
                    <ul class="mt-4 space-y-3">
                        <li>
                            <a
                                href="mailto:leodamascenodev@proton.me"
                                class="text-sm text-[#64748b] transition-colors hover:text-[#2563eb]"
                            >
                                Email
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://github.com/leonardohenriquedc"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-sm text-[#64748b] transition-colors hover:text-[#2563eb]"
                            >
                                GitHub
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.linkedin.com/in/leonardohenriquedc"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-sm text-[#64748b] transition-colors hover:text-[#2563eb]"
                            >
                                LinkedIn
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Redes -->
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-[#111827]">
                        Redes
                    </h3>
                    <p class="mt-4 text-sm text-[#64748b]">
                        Me acompanhe nas plataformas
                    </p>
                    <ul class="mt-5 flex gap-3" aria-label="Redes sociais">
                        <li>
                            <a
                                href="https://github.com/leonardohenriquedc"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="GitHub"
                                class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-[#64748b] transition-colors hover:border-[#2563eb] hover:text-[#2563eb]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2Z"
                                    />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.linkedin.com/in/leonardohenriquedc"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="LinkedIn"
                                class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-[#64748b] transition-colors hover:border-[#2563eb] hover:text-[#2563eb]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286ZM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065Zm1.782 13.019H3.555V9h3.564v11.452ZM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0Z"
                                    />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a
                                href="mailto:leodamascenodev@proton.me"
                                aria-label="Email"
                                class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-[#64748b] transition-colors hover:border-[#2563eb] hover:text-[#2563eb]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <rect
                                        x="2"
                                        y="4"
                                        width="20"
                                        height="16"
                                        rx="2"
                                    />
                                    <path
                                        d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"
                                    />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200">
            <div class="mx-auto max-w-5xl px-5 py-6 sm:px-8">
                <p class="text-center text-sm text-[#94a3b8]">
                    © 2026 Leonardo Henrique — Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>
</template>
