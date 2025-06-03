<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <nav class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0">
                        <Link href="/" class="text-xl font-bold text-gray-800">
                            Proyecto Hoteles
                        </Link>
                    </div>
                    <div class="space-x-4">
                        <Link href="/customers" class="text-gray-700 hover:text-gray-900">Clientes</Link>
                        <a href="/about" class="text-gray-700 hover:text-gray-900">Acerca</a>
                        <a href="/contact" class="text-gray-700 hover:text-gray-900">Contacto</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
