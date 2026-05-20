<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";

import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import { Input } from "@/Components/ui/input";

// ShadCN Card Components
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

defineProps<{
    canRegister?: boolean;
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
        },
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
        <Card class="w-full max-w-md shadow-xl">
            <CardHeader class="space-y-1">
                <CardTitle class="text-2xl font-bold"> Login </CardTitle>

                <CardDescription>
                    Enter your email and password to access your account
                </CardDescription>
            </CardHeader>

            <CardContent>
                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="email" value="Email" />

                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" />

                        <Input
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                            />

                            <span class="ms-2 text-sm text-gray-600">
                                Remember me
                            </span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-primary underline-offset-4 hover:underline italic"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <PrimaryButton
                        class="w-full justify-center"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </PrimaryButton>
                </form>
            </CardContent>

            <CardFooter class="flex justify-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="ml-1 text-primary underline-offset-4 hover:underline"
                    >
                        Register
                    </Link>
                </p>
            </CardFooter>
        </Card>
    </div>
</template>
