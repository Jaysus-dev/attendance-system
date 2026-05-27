<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { Button } from "@/Components/ui/button";

const props = defineProps<{
    users: Array<any>;
}>();

const form = useForm({});
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <div class="p-6">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Role</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Action</TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="user in props.users" :key="user.id">
                        <TableCell>{{ user.name }}</TableCell>
                        <TableCell>{{ user.email }}</TableCell>
                        <TableCell>{{ user.role }}</TableCell>
                        <TableCell>
                            <span
                                :class="
                                    user.status === 'approved'
                                        ? 'text-green-600'
                                        : 'text-yellow-600'
                                "
                            >
                                {{ user.status }}
                            </span>
                        </TableCell>

                        <TableCell class="flex gap-2">
                            <Button
                                v-if="user.status !== 'approved'"
                                size="sm"
                                @click="
                                    form.put(
                                        route('admin.users.approve', user.id),
                                    )
                                "
                            >
                                Approve
                            </Button>

                            <Button
                                size="sm"
                                variant="destructive"
                                @click="
                                    form.delete(
                                        route('admin.users.reject', user.id),
                                    )
                                "
                            >
                                Reject
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AuthenticatedLayout>
</template>
