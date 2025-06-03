<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";

// Recibimos las props desde Laravel/Inertia
const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String
});

defineOptions({
    name: "Welcome",
    layout: MainLayout,
});
</script>

<template>
    <div>
        <h1 class="text-3xl font-bold mb-6">Bienvenido a Proyecto Hoteles</h1>

        <p class="mb-4">
            Laravel versión: <strong>{{ laravelVersion }}</strong><br>
            PHP versión: <strong>{{ phpVersion }}</strong>
        </p>

        <div v-if="canLogin || canRegister" class="mb-6 space-x-4">
            <a v-if="canLogin" href="/login" class="text-blue-600 hover:underline">Login</a>
            <a v-if="canRegister" href="/register" class="text-blue-600 hover:underline">Registrarse</a>
        </div>
    </div>
</template>

