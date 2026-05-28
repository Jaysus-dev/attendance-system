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
import { Search } from "lucide-vue-next";

const props = defineProps({
    teacher: Object,
    assignments: {
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

const filteredAssignments = computed(() => {
    return props.assignments.filter((a) => {
        const q = search.value.toLowerCase();

        return (
            a.course?.course_name?.toLowerCase().includes(q) ||
            a.section?.section_name?.toLowerCase().includes(q) ||
            a.subject?.subject_name?.toLowerCase().includes(q)
        );
    });
});
</script>

<template>
    <Head title="Teacher Classes" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <CardHeader>
                    <!-- TITLE -->
                    <div>
                        <CardTitle class="text-2xl">
                            {{ teacher.fullname }}
                        </CardTitle>

                        <CardDescription> Assigned Classes </CardDescription>
                    </div>

                    <!-- SEARCH (same style as attendance) -->
                    <div class="flex gap-4 mt-4">
                        <div class="relative w-full max-w-sm">
                            <Search
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
                            />

                            <Input
                                v-model="search"
                                placeholder="Search class..."
                                class="pl-10"
                            />
                        </div>
                    </div>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Course</TableHead>
                                <TableHead>Section</TableHead>
                                <TableHead>Subject</TableHead>
                                <TableHead class="text-right">
                                    Action
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="a in filteredAssignments"
                                :key="a.id"
                                class="cursor-pointer"
                                size="sm"
                                @click="
                                    $inertia.visit(
                                        route('attendance.take', a.id),
                                    )
                                "
                            >
                                <TableCell class="font-medium">
                                    {{ a.course.course_name }}
                                </TableCell>

                                <TableCell>
                                    {{ a.section.section_name }}
                                </TableCell>

                                <TableCell>
                                    {{ a.subject.subject_name }}
                                </TableCell>

                                <TableCell class="text-right">
                                    <Button> Take Attendance </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredAssignments.length === 0">
                                <TableCell
                                    colspan="4"
                                    class="text-center py-8 text-muted-foreground"
                                >
                                    No classes found
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
