<script setup>
import { Link, Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Pending Approval" />
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded shadow text-center max-w-md">
            <h1 class="text-xl font-bold">Account Pending Approval</h1>

            <p class="mt-2 text-gray-600">
                Your account is waiting for admin approval.
            </p>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="mt-4 inline-block text-red-500 underline"
            >
                Logout
            </Link>
        </div>
    </div>
</template>
