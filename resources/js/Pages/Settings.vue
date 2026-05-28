<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router, usePage, Head } from "@inertiajs/vue3";

const user = usePage().props.auth.user;

const updateStyle = (style: number) => {
    router.patch(
        route("settings.dashboard-style"),
        {
            dashboard_style: style,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                router.visit(route("dashboard")); // ✅ BEST
            },
        },
    );
};
</script>

<template>
    <Head title="Settings" />
    <AuthenticatedLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-xl font-bold">Dashboard Styles</h1>

            <p class="text-sm text-gray-500">
                Current Style: {{ user.dashboard_style }}
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button
                    v-for="n in 9"
                    :key="n"
                    @click="updateStyle(n)"
                    class="border rounded-lg p-4 hover:bg-gray-100 transition"
                    :class="
                        user.dashboard_style == n
                            ? 'bg-blue-100 border-blue-500'
                            : ''
                    "
                >
                    Dashboard Style {{ n }}
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
