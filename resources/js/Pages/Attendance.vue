<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
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
import { Layers } from "lucide-vue-next";

const props = defineProps({
    assignments: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Attendance" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>My Classes</CardTitle>
                            <CardDescription>
                                Your assigned classes for attendance
                            </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Layers class="w-4 h-4" />
                            <span
                                >Total:
                                {{ props.assignments?.length ?? 0 }}</span
                            >
                        </div>
                    </div>
                </CardHeader>

                <CardContent>
                    <!-- TABLE -->
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Course</TableHead>
                                <TableHead>Section</TableHead>
                                <TableHead>Subject</TableHead>
                                <TableHead>Year Level</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="a in props.assignments"
                                :key="a.id"
                            >
                                <TableCell>
                                    {{ a.course.course_name }}
                                </TableCell>

                                <TableCell>
                                    {{ a.section.section_name }}
                                </TableCell>

                                <TableCell>
                                    {{ a.subject.subject_name }}
                                </TableCell>
                                <TableCell>
                                    {{ a.section?.year_level || "Not Set" }}
                                </TableCell>
                                <TableCell>
                                    <Button size="sm">Take Attendance</Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="assignments.length === 0">
                                <TableCell
                                    colspan="4"
                                    class="text-center py-10"
                                >
                                    No assigned classes found.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
