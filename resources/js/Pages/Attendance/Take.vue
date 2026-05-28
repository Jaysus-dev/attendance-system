<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed, ref, reactive } from "vue";

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

import { Input } from "@/Components/ui/input";
import { Users } from "lucide-vue-next";

/* --------------------------
   PROPS
--------------------------- */
const props = defineProps<{
    assignment: any;
    students: any[];
    attendance: Record<string, any>;
}>();

/* --------------------------
   LOCAL STATE (IMPORTANT FIX)
--------------------------- */
const localAttendance = reactive<Record<string, any>>({
    ...props.attendance,
});

/* --------------------------
   SEARCH
--------------------------- */
const search = ref("");

const filteredStudents = computed(() => {
    const q = search.value.toLowerCase();

    return props.students.filter((s) => {
        return (
            s.fullname.toLowerCase().includes(q) ||
            s.student_number.toLowerCase().includes(q) ||
            s.email.toLowerCase().includes(q)
        );
    });
});

/* --------------------------
   KEY
--------------------------- */
const getKey = (studentId: number) => `${studentId}-${props.assignment.id}`;

/* --------------------------
   GET STATUS
--------------------------- */
const getStatus = (studentId: number) => {
    return localAttendance[getKey(studentId)]?.status;
};

/* --------------------------
   LOCK (5 MINUTES FIX)
--------------------------- */
const isLocked = (studentId: number) => {
    const record = localAttendance[getKey(studentId)];

    if (!record?.created_at) return false;

    const minutes =
        (Date.now() - new Date(record.created_at).getTime()) / 60000;

    return minutes >= 5;
};

/* --------------------------
   MARK ATTENDANCE
--------------------------- */
const mark = (status: string, studentId: number) => {
    const key = getKey(studentId);

    // update UI instantly
    localAttendance[key] = {
        student_id: studentId,
        class_assignment_id: props.assignment.id,
        status,
        created_at:
            localAttendance[key]?.created_at ?? new Date().toISOString(),
    };

    router.post(
        route("attendance.mark"),
        {
            student_id: studentId,
            class_assignment_id: props.assignment.id,
            status,
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
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
                                    <!-- LOCKED -->
                                    <div
                                        v-if="isLocked(student.id)"
                                        class="font-bold"
                                    >
                                        {{ getStatus(student.id) }}
                                    </div>

                                    <!-- ACTIVE -->
                                    <div v-else class="flex gap-2">
                                        <button
                                            :class="[
                                                'px-3 py-1 rounded-md border',
                                                getStatus(student.id) === 'late'
                                                    ? 'bg-yellow-500 text-white'
                                                    : 'bg-yellow-100 text-yellow-700',
                                            ]"
                                            @click="mark('late', student.id)"
                                        >
                                            Late
                                        </button>

                                        <button
                                            :class="[
                                                'px-3 py-1 rounded-md border',
                                                getStatus(student.id) ===
                                                'present'
                                                    ? 'bg-green-600 text-white'
                                                    : 'bg-green-100 text-green-700',
                                            ]"
                                            @click="mark('present', student.id)"
                                        >
                                            Present
                                        </button>

                                        <button
                                            :class="[
                                                'px-3 py-1 rounded-md border',
                                                getStatus(student.id) ===
                                                'absent'
                                                    ? 'bg-red-600 text-white'
                                                    : 'bg-red-100 text-red-700',
                                            ]"
                                            @click="mark('absent', student.id)"
                                        >
                                            Absent
                                        </button>
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
