<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from "@/Components/ui/card";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";

import { computed, ref } from "vue";
import { Search, Users } from "lucide-vue-next";

const props = defineProps<{
    users: Array<any>;
}>();

const form = useForm({});

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
const search = ref("");

const filteredUsers = computed(() => {
    const q = search.value.toLowerCase();

    return props.users.filter((u) => {
        return (
            u.name.toLowerCase().includes(q) ||
            u.email.toLowerCase().includes(q) ||
            u.role.toLowerCase().includes(q)
        );
    });
});
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <!-- HEADER CARD -->
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>User Management</CardTitle>
                            <CardDescription>
                                Manage system users and approvals
                            </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Users class="w-4 h-4" />
                            <span>Total: {{ filteredUsers.length }}</span>
                        </div>
                    </div>

                    <!-- SEARCH BAR -->
                    <div class="mt-4 relative max-w-sm">
                        <Search
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
                        />

                        <Input
                            v-model="search"
                            placeholder="Search users..."
                            class="pl-10"
                        />
                    </div>
                </CardHeader>
            </Card>

            <!-- TABLE CARD -->
            <Card>
                <CardContent class="pt-6">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Role</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-right">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="user in filteredUsers"
                                :key="user.id"
                            >
                                <TableCell class="font-medium">
                                    {{ user.name }}
                                </TableCell>

                                <TableCell>
                                    {{ user.email }}
                                </TableCell>

                                <TableCell class="capitalize">
                                    {{ user.role }}
                                </TableCell>

                                <TableCell>
                                    <span
                                        :class="
                                            user.status === 'approved'
                                                ? 'text-green-600 capitalize font-semibold'
                                                : 'text-yellow-600 capitalize font-semibold'
                                        "
                                    >
                                        {{ user.status }}
                                    </span>
                                </TableCell>

                                <TableCell class="text-right space-x-2">
                                    <Button
                                        v-if="user.status !== 'approved'"
                                        size="sm"
                                        @click="
                                            form.put(
                                                route(
                                                    'admin.users.approve',
                                                    user.id,
                                                ),
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
                                                route(
                                                    'admin.users.reject',
                                                    user.id,
                                                ),
                                            )
                                        "
                                    >
                                        Reject
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredUsers.length === 0">
                                <TableCell
                                    colspan="5"
                                    class="text-center py-8 text-muted-foreground"
                                >
                                    No users found
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
