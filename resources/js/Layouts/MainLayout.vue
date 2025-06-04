<script setup>
import {Link} from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import 'primeicons/primeicons.css';

const { t } = useI18n();
</script>

<template>
    <div class="w-full min-h-screen bg-blue-50 flex flex-col">
        <nav class="sticky top-0 left-0 right-0 bg-white shadow-md z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0">
                        <Link href="/" class="text-xl font-bold text-gray-800">
                            {{ t('messages.app_name') }}
                        </Link>
                    </div>
                    <div class="space-x-4">
                        <Link v-if="$page.props.auth.is_admin" href="/users" class="text-gray-700 hover:text-gray-900">
                            {{ t('messages.main_menu.users') }}
                        </Link>
                        <a href="/about" class="text-gray-700 hover:text-gray-900">
                            {{ t('messages.main_menu.about') }}
                        </a>
                        <Link href="/attractions" class="text-gray-700 hover:text-gray-900">
                            {{ t('messages.main_menu.attractions') }}
                        </Link>
                        <a href="/contact" class="text-gray-700 hover:text-gray-900">
                            {{ t('messages.main_menu.contact') }}
                        </a>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="`/profile/${$page.props.auth.user.id}`"
                            class="text-gray-700 hover:text-gray-900"
                        >
                            {{ t('messages.main_menu.profile') }}
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <slot />
        </main>

        <div
            v-if="$page.props.auth.impersonating"
            class="bg-yellow-200 text-yellow-900 text-center py-3 px-4 shadow-inner fixed bottom-0 left-0 right-0 z-50"
        >
            <i class="pi pi-exclamation-triangle" /> {{ t('messages.impersonating_warning_1') }} {{ $page.props.auth.user.name }}

            <Link
                icon="pi pi-times"
                class="ml-2 p-1 hover:text-yellow-600 rounded-full underline cursor-pointer"
                href="/impersonate/stop"
            >
                Detener Impersonación
            </Link>
        </div>
    </div>
</template>

