<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

import { Field, FieldDescription, FieldLabel } from "@/Components/ui/field";

import { Input } from "@/Components/ui/input";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "student",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
        },
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
        <Card class="w-full max-w-md">
            <CardHeader>
                <CardTitle>Create an account</CardTitle>

                <CardDescription>
                    Enter your information below to create your account
                </CardDescription>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="submit" class="space-y-4">
                    <!-- NAME -->
                    <Field>
                        <FieldLabel for="name"> Full Name </FieldLabel>

                        <Input
                            id="name"
                            type="text"
                            placeholder="John Doe"
                            v-model="form.name"
                            required
                        />

                        <p
                            v-if="form.errors.name"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ form.errors.name }}
                        </p>
                    </Field>

                    <!-- EMAIL -->
                    <Field>
                        <FieldLabel for="email"> Email </FieldLabel>

                        <Input
                            id="email"
                            type="email"
                            placeholder="m@example.com"
                            v-model="form.email"
                            required
                        />

                        <FieldDescription>
                            We'll use this to contact you.
                        </FieldDescription>

                        <p
                            v-if="form.errors.email"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ form.errors.email }}
                        </p>
                    </Field>

                    <!-- ROLE -->
                    <Field>
                        <FieldLabel for="role"> Register As </FieldLabel>

                        <select
                            id="role"
                            v-model="form.role"
                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        >
                            <option value="student">Student</option>

                            <option value="teacher">Teacher</option>
                        </select>

                        <p
                            v-if="form.errors.role"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ form.errors.role }}
                        </p>
                    </Field>

                    <!-- PASSWORD -->
                    <Field>
                        <FieldLabel for="password"> Password </FieldLabel>

                        <Input
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                        />

                        <FieldDescription>
                            Must be at least 8 characters long.
                        </FieldDescription>

                        <p
                            v-if="form.errors.password"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ form.errors.password }}
                        </p>
                    </Field>

                    <!-- CONFIRM PASSWORD -->
                    <Field>
                        <FieldLabel for="password_confirmation">
                            Confirm Password
                        </FieldLabel>

                        <Input
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            required
                        />

                        <p
                            v-if="form.errors.password_confirmation"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ form.errors.password_confirmation }}
                        </p>
                    </Field>

                    <!-- BUTTON -->
                    <div class="space-y-2 pt-2">
                        <PrimaryButton
                            type="submit"
                            class="w-full justify-center"
                            :disabled="form.processing"
                        >
                            Create Account
                        </PrimaryButton>
                    </div>

                    <!-- LOGIN -->
                    <p class="text-center text-sm mt-4">
                        Already have an account?

                        <Link
                            :href="route('login')"
                            class="text-primary underline ml-1"
                        >
                            Sign in
                        </Link>
                    </p>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
