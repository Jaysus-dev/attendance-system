<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

import { Field, FieldLabel } from "@/Components/ui/field";
import { Input } from "@/Components/ui/input";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
        <Card class="w-full max-w-md">
            <CardHeader>
                <CardTitle>Forgot Password</CardTitle>

                <CardDescription>
                    Enter your email address and we will send you a password
                    reset link.
                </CardDescription>
            </CardHeader>

            <CardContent>
                <!-- STATUS MESSAGE -->
                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- EMAIL -->
                    <Field>
                        <FieldLabel for="email">Email</FieldLabel>

                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            placeholder="m@example.com"
                            required
                            autofocus
                        />

                        <p
                            v-if="form.errors.email"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ form.errors.email }}
                        </p>
                    </Field>

                    <!-- BUTTON -->
                    <div class="pt-2">
                        <PrimaryButton
                            type="submit"
                            class="w-full justify-center"
                            :disabled="form.processing"
                        >
                            Email Password Reset Link
                        </PrimaryButton>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
