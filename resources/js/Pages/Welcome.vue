<template>
    <Head :title="t('messages.welcome')" />
    <div v-if="!$page.props.auth.user" class="w-full h-full min-h-screen bg-gradient-to-br from-blue-500 via-blue-100 to-blue-300 flex items-center justify-center">
        <div class="bg-white shadow-2xl rounded-2xl p-10 max-w-2xl w-full text-center space-y-6">
            <h1 class="text-4xl font-bold text-blue-800">
                {{ t('messages.welcome') }}
            </h1>
            <p class="text-gray-600 text-lg">
                {{ t('messages.description') }}
            </p>
            <div class="mt-8">
                <a
                    href="/register"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full transition"
                >
                    {{ t('messages.get_started') }}
                </a>
            </div>
            <div class="mt-20">
                <Link
                    href="/attractions"
                    class="text-blue-600 hover:text-blue-800 text-sm underline">
                    {{ t('messages.attraction_link') }}
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import { useI18n } from 'vue-i18n'
import {Head, usePage} from "@inertiajs/vue3";

const { t } = useI18n()
const page = usePage()
const user = page.props.auth?.user

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String
});

</script>


