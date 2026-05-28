<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed, ref } from "vue";

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
import { Search, Users } from "lucide-vue-next";

const props = defineProps({
    teachers: {
        type: Array,
        default: () => [],
    },
});

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
const search = ref("");

const filteredTeachers = computed(() => {
    return props.teachers.filter((t) => {
        const q = search.value.toLowerCase();

        return (
            t.fullname?.toLowerCase().includes(q) ||
            t.email?.toLowerCase().includes(q)
        );
    });
});
</script>

<template>
    <Head title="Teachers" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <CardHeader>
                    <!-- HEADER -->
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>Teachers</CardTitle>
                            <CardDescription>
                                List of all teachers
                            </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Users class="w-4 h-4" />
                            <span> Total: {{ filteredTeachers.length }} </span>
                        </div>
                    </div>

                    <!-- SEARCH (same style as attendance) -->
                    <div class="flex gap-4 mt-4">
                        <div class="relative w-full max-w-sm">
                            <Search
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
                            />

                            <Input
                                v-model="search"
                                placeholder="Search teacher..."
                                class="pl-10"
                            />
                        </div>
                    </div>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="teacher in filteredTeachers"
                                :key="teacher.id"
                                class="cursor-pointer"
                                size="sm"
                                @click="
                                    $inertia.visit(
                                        route(
                                            'admin.teacher.classes',
                                            teacher.id,
                                        ),
                                    )
                                "
                            >
                                <TableCell class="font-medium">
                                    {{ teacher.fullname }}
                                </TableCell>

                                <TableCell>
                                    {{ teacher.email }}
                                </TableCell>

                                <TableCell class="text-right">
                                    <Button> View Classes </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredTeachers.length === 0">
                                <TableCell
                                    colspan="3"
                                    class="text-center py-8 text-muted-foreground"
                                >
                                    No teachers found
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
