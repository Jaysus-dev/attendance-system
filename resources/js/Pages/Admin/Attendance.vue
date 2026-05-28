<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { Button } from "@/Components/ui/button";

defineProps({
    assignments: Array,
});
</script>

<template>
    <Head title="Admin Attendance" />

    <AuthenticatedLayout>
        <div class="p-6">
            <Card>
                <CardHeader>
                    <CardTitle>Teacher Classes</CardTitle>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Teacher</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Section</TableHead>
                                <TableHead>Subject</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow v-for="a in assignments" :key="a.id">
                                <TableCell>
                                    {{ a.teacher.fullname }}
                                </TableCell>

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
                                    <Button
                                        @click="
                                            $inertia.visit(
                                                route('attendance.take', a.id),
                                            )
                                        "
                                    >
                                        Take Attendance
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
