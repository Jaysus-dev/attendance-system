<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

/* SHADCN */
import { Card, CardHeader, CardTitle, CardContent } from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import {
    Select,
    SelectTrigger,
    SelectContent,
    SelectItem,
    SelectValue,
} from "@/Components/ui/select";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    attendances: any[];
    courses: any[];
    subjects: any[];
    filters?: any;
}>();

/*
|--------------------------------------------------------------------------
| FILTER STATE
|--------------------------------------------------------------------------
*/
const start_date = ref(props.filters?.start_date ?? "");
const end_date = ref(props.filters?.end_date ?? "");
const course_id = ref(props.filters?.course_id ?? "all");
const subject_id = ref(props.filters?.subject_id ?? "all");

/*
|--------------------------------------------------------------------------
| NORMALIZE STATUS (IMPORTANT FIX 🔥)
|--------------------------------------------------------------------------
*/
const normalizeStatus = (status: string) => {
    if (!status) return "";
    return status.toLowerCase();
};

/*
|--------------------------------------------------------------------------
| FILTER ACTION
|--------------------------------------------------------------------------
*/
const filter = () => {
    router.get(route("reports"), {
        start_date: start_date.value,
        end_date: end_date.value,
        course_id: course_id.value === "all" ? null : course_id.value,
        subject_id: subject_id.value === "all" ? null : subject_id.value,
    });
};

/*
|--------------------------------------------------------------------------
| SINGLE SOURCE OF TRUTH (NO LOCAL COPY)
|--------------------------------------------------------------------------
*/
const filteredAttendances = computed(() => props.attendances);

const downloadPdf = () => {
    window.open(
        route("reports.pdf", {
            start_date: start_date.value,
            end_date: end_date.value,
            course_id: course_id.value,
            subject_id: subject_id.value,
        }),
        "_blank",
    );
};
/*
|--------------------------------------------------------------------------
| COUNTERS (FIXED SYNC)
|--------------------------------------------------------------------------
*/
const totalPresent = computed(
    () =>
        filteredAttendances.value.filter(
            (a) => normalizeStatus(a.status) === "present",
        ).length,
);

const totalAbsent = computed(
    () =>
        filteredAttendances.value.filter(
            (a) => normalizeStatus(a.status) === "absent",
        ).length,
);

const totalLate = computed(
    () =>
        filteredAttendances.value.filter(
            (a) => normalizeStatus(a.status) === "late",
        ).length,
);
</script>

<template>
    <Head title="Attendance Reports" />

    <AuthenticatedLayout>
        <div class="p-6 space-y-6">
            <!-- MAIN CARD -->
            <Card>
                <CardHeader>
                    <CardTitle>Attendance Report</CardTitle>

                    <!-- FILTERS -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mt-4">
                        <Input type="date" v-model="start_date" />
                        <Input type="date" v-model="end_date" />

                        <!-- COURSE -->
                        <Select v-model="course_id">
                            <SelectTrigger>
                                <SelectValue placeholder="Course" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Courses</SelectItem>
                                <SelectItem
                                    v-for="c in courses"
                                    :key="c.id"
                                    :value="String(c.id)"
                                >
                                    {{ c.course_name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <!-- SUBJECT -->
                        <Select v-model="subject_id">
                            <SelectTrigger>
                                <SelectValue placeholder="Subject" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all"
                                    >All Subjects</SelectItem
                                >
                                <SelectItem
                                    v-for="s in subjects"
                                    :key="s.id"
                                    :value="String(s.id)"
                                >
                                    {{ s.subject_name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <Button @click="filter">Filter</Button>
                    </div>

                    <!-- STATS (FIXED + ALWAYS SYNCED) -->
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="p-4 border rounded-lg text-center">
                            <div class="text-green-600 text-2xl font-bold">
                                {{ totalPresent }}
                            </div>
                            <div class="text-sm text-muted-foreground">
                                Present
                            </div>
                        </div>

                        <div class="p-4 border rounded-lg text-center">
                            <div class="text-red-600 text-2xl font-bold">
                                {{ totalAbsent }}
                            </div>
                            <div class="text-sm text-muted-foreground">
                                Absent
                            </div>
                        </div>

                        <div class="p-4 border rounded-lg text-center">
                            <div class="text-yellow-600 text-2xl font-bold">
                                {{ totalLate }}
                            </div>
                            <div class="text-sm text-muted-foreground">
                                Late
                            </div>
                        </div>
                    </div>
                    <Button class="mt-4" @click="downloadPdf">
                        Export PDF
                    </Button>
                </CardHeader>
            </Card>

            <!-- TABLE -->
            <Card>
                <CardHeader>
                    <CardTitle>Attendance Records</CardTitle>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Student</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Subject</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Date</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="a in filteredAttendances"
                                :key="a.id"
                            >
                                <TableCell>
                                    {{ a.student?.fullname }}
                                </TableCell>

                                <TableCell>
                                    {{
                                        a.class_assignment?.course?.course_name
                                    }}
                                </TableCell>

                                <TableCell>
                                    {{
                                        a.class_assignment?.subject
                                            ?.subject_name
                                    }}
                                </TableCell>

                                <TableCell>
                                    <span
                                        :class="{
                                            'text-green-600 font-semibold uppercase':
                                                normalizeStatus(a.status) ===
                                                'present',
                                            'text-red-600 font-semibold uppercase':
                                                normalizeStatus(a.status) ===
                                                'absent',
                                            'text-yellow-600 font-semibold uppercase':
                                                normalizeStatus(a.status) ===
                                                'late',
                                        }"
                                    >
                                        {{ a.status }}
                                    </span>
                                </TableCell>

                                <TableCell>
                                    {{ a.date }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
