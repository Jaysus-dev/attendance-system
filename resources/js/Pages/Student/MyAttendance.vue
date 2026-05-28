<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

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

import { CalendarDays } from "lucide-vue-next";

defineProps({
    attendance: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="My Attendance" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>My Attendance</CardTitle>

                            <CardDescription>
                                View your attendance records
                            </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <CalendarDays class="w-4 h-4" />

                            <span>
                                Total:
                                {{ attendance.length }}
                            </span>
                        </div>
                    </div>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Date</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Section</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow v-for="a in attendance" :key="a.id">
                                <TableCell>
                                    {{ a.date }}
                                </TableCell>

                                <TableCell>
                                    <span
                                        class="px-2 py-1 rounded text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-700':
                                                a.status === 'Present',

                                            'bg-red-100 text-red-700':
                                                a.status === 'Absent',

                                            'bg-yellow-100 text-yellow-700':
                                                a.status === 'Late',
                                        }"
                                    >
                                        {{ a.status }}
                                    </span>
                                </TableCell>

                                <TableCell>
                                    {{ a.course?.course_name ?? "N/A" }}
                                </TableCell>

                                <TableCell>
                                    {{ a.section?.section_name ?? "N/A" }}
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="attendance.length === 0">
                                <TableCell
                                    colspan="4"
                                    class="text-center py-10"
                                >
                                    No attendance records found.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
