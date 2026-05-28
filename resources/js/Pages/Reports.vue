<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

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
|----------------------------------
| PROPS
|----------------------------------
*/
const props = defineProps<{
    attendances: any[];
    courses: any[];
    subjects: any[];
    filters?: any;
}>();

/*
|----------------------------------
| 🔥 FIX: LOCAL REACTIVE COPY
|----------------------------------
*/
const localAttendances = ref([...props.attendances]);

watch(
    () => props.attendances,
    (newVal) => {
        localAttendances.value = [...newVal];
    },
);

/*
|----------------------------------
| FILTER STATE
|----------------------------------
*/
const start_date = ref(props.filters?.start_date ?? "");
const end_date = ref(props.filters?.end_date ?? "");
const course_id = ref(props.filters?.course_id ?? "all");
const subject_id = ref(props.filters?.subject_id ?? "all");

/*
|----------------------------------
| FILTER ACTION
|----------------------------------
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
|----------------------------------
| COUNTERS (NOW FIXED SYNC ISSUE)
|----------------------------------
*/
const totalPresent = computed(
    () => localAttendances.value.filter((a) => a.status === "Present").length,
);

const totalAbsent = computed(
    () => localAttendances.value.filter((a) => a.status === "Absent").length,
);

const totalLate = computed(
    () => localAttendances.value.filter((a) => a.status === "Late").length,
);
</script>

<template>
    <Head title="Attendance Reports" />

    <AuthenticatedLayout>
        <div class="p-6 space-y-6">
            <!-- FILTER + STATS CARD -->
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

                    <!-- STATS -->
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="p-4 border rounded-lg text-center">
                            <div class="text-green-600 text-xl font-bold">
                                {{ totalPresent }}
                            </div>
                            <div class="text-sm text-muted-foreground">
                                Present
                            </div>
                        </div>

                        <div class="p-4 border rounded-lg text-center">
                            <div class="text-red-600 text-xl font-bold">
                                {{ totalAbsent }}
                            </div>
                            <div class="text-sm text-muted-foreground">
                                Absent
                            </div>
                        </div>

                        <div class="p-4 border rounded-lg text-center">
                            <div class="text-yellow-600 text-xl font-bold">
                                {{ totalLate }}
                            </div>
                            <div class="text-sm text-muted-foreground">
                                Late
                            </div>
                        </div>
                    </div>
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
                            <TableRow v-for="a in localAttendances" :key="a.id">
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
                                            'text-green-600 font-semibold':
                                                a.status === 'Present',
                                            'text-red-600 font-semibold':
                                                a.status === 'Absent',
                                            'text-yellow-600 font-semibold':
                                                a.status === 'Late',
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
