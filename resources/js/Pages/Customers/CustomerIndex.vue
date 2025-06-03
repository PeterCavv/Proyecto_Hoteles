<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";

defineProps({
    customers: Object,
});

defineOptions({
    name: "CustomerIndex",
    layout: MainLayout,
});
</script>

<template>
    <Head title="Customers" />

    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 border-b pb-4">
            Lista de Clientes
        </h1>

        <section>
            <template v-if="customers.length > 0">
                <ul class="space-y-6">
                    <li
                        v-for="customer in customers"
                        :key="customer.id"
                        class="p-4 border rounded-lg shadow-sm hover:shadow-md transition-shadow bg-white"
                    >
                        <h2 class="text-2xl font-semibold text-indigo-600 mb-1">
                            {{ customer.name }}
                        </h2>
                        <p class="text-gray-700">{{ customer.email }}</p>
                    </li>
                </ul>
            </template>

            <template v-else>
                <p class="text-center text-gray-500 italic">No customers found.</p>
            </template>
        </section>

        <div class="mt-10 text-center">
            <Button
                href="/customers"
            >
                View All Customers
            </Button>
        </div>
    </div>
</template>

