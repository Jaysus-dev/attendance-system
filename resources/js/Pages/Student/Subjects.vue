<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
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
import { Search, BookOpen } from "lucide-vue-next";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    subjects: Array<any>;
}>();

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
const search = ref("");

const filteredSubjects = computed(() => {
    return props.subjects.filter((s) => {
        const q = search.value.toLowerCase();

        return (
            s.subject_name?.toLowerCase().includes(q) ||
            s.subject_code?.toLowerCase().includes(q)
        );
    });
});

/*
|--------------------------------------------------------------------------
| NAVIGATION
|--------------------------------------------------------------------------
*/
function openSubject(id: number) {
    router.visit(`/my-subjects/${id}/classes`);
}
</script>

<template>
    <Head title="My Subjects" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <!-- CARD -->
            <Card>
                <CardHeader>
                    <!-- HEADER -->
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>My Subjects</CardTitle>
                            <CardDescription>
                                List of assigned subjects
                            </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <BookOpen class="w-4 h-4" />
                            <span>Total: {{ filteredSubjects.length }}</span>
                        </div>
                    </div>

                    <!-- SEARCH -->
                    <div class="flex gap-4 mt-4">
                        <div class="relative w-full max-w-sm">
                            <Search
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
                            />

                            <Input
                                v-model="search"
                                placeholder="Search subject..."
                                class="pl-10"
                            />
                        </div>
                    </div>
                </CardHeader>

                <CardContent>
                    <!-- TABLE -->
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Subject Code</TableHead>
                                <TableHead>Subject Name</TableHead>
                                <TableHead class="text-right">
                                    Action
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="subject in filteredSubjects"
                                :key="subject.id"
                                class="cursor-pointer hover:bg-muted/50"
                            >
                                <TableCell class="font-medium">
                                    {{ subject.subject_code }}
                                </TableCell>

                                <TableCell>
                                    {{ subject.subject_name }}
                                </TableCell>

                                <TableCell class="text-right">
                                    <Button
                                        size="sm"
                                        @click="openSubject(subject.id)"
                                    >
                                        View Classes
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredSubjects.length === 0">
                                <TableCell
                                    colspan="3"
                                    class="text-center py-8 text-muted-foreground"
                                >
                                    No subjects found
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
