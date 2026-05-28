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
import { Search, Users } from "lucide-vue-next";

const props = defineProps<{
    assignment: any;
    students: any[];
    attendance: Record<number, any>;
}>();

const search = ref("");
const selectedYear = ref("");

/* --------------------------
   FILTERED STUDENTS
--------------------------- */
const filteredStudents = computed(() => {
    return props.students.filter((s) => {
        const q = search.value.toLowerCase();

        return (
            s.fullname.toLowerCase().includes(q) ||
            s.student_number.toLowerCase().includes(q) ||
            s.email.toLowerCase().includes(q)
        );
    });
});

/* --------------------------
   ATTENDANCE MAP
--------------------------- */
const statusMap = computed(() => props.attendance || {});

/* --------------------------
   LOCK AFTER 5 MINUTES
--------------------------- */
const isLocked = (studentId: number) => {
    const record = props.attendance?.[studentId];

    if (!record) return false;

    const minutes =
        (Date.now() - new Date(record.created_at).getTime()) / 60000;

    return minutes >= 5;
};

/* --------------------------
   MARK ATTENDANCE
--------------------------- */
const mark = (status: string, studentId: number) => {
    router.post(route("attendance.mark"), {
        student_id: studentId,
        status,
    });
};
</script>

<template>
    <Head title="Take Attendance" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <!-- HEADER -->
            <Card>
                <CardHeader>
                    <CardTitle>
                        {{ assignment.course.course_name }} -
                        {{ assignment.section.section_name }}
                    </CardTitle>

                    <CardDescription>
                        {{ assignment.subject.subject_name }}
                    </CardDescription>
                </CardHeader>
            </Card>

            <!-- STUDENTS -->
            <Card>
                <CardHeader>
                    <div class="flex justify-between">
                        <CardTitle>Students</CardTitle>
                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Users class="w-4 h-4" />
                            <span>Total: {{ filteredStudents.length }}</span>
                        </div>
                    </div>

                    <!-- SEARCH -->
                    <div class="mt-4">
                        <Input
                            v-model="search"
                            placeholder="Search student..."
                        />
                    </div>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Student #</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="student in filteredStudents"
                                :key="student.id"
                            >
                                <TableCell>{{
                                    student.student_number
                                }}</TableCell>
                                <TableCell>{{ student.fullname }}</TableCell>
                                <TableCell>{{ student.email }}</TableCell>

                                <TableCell>
                                    <!-- NOT LOCKED -->
                                    <div
                                        v-if="!isLocked(student.id)"
                                        class="flex gap-2"
                                    >
                                        <Button
                                            @click="mark('late', student.id)"
                                        >
                                            Late
                                        </Button>

                                        <Button
                                            @click="mark('present', student.id)"
                                        >
                                            Present
                                        </Button>

                                        <Button
                                            @click="mark('absent', student.id)"
                                        >
                                            Absent
                                        </Button>
                                    </div>

                                    <!-- LOCKED -->
                                    <div v-else class="font-bold">
                                        {{ statusMap[student.id]?.status }}
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
