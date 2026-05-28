<script setup lang="ts">
/*
|--------------------------------------------------------------------------
| Imports
|--------------------------------------------------------------------------
*/
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

import { Button } from "@/Components/ui/button";

import { Input } from "@/Components/ui/input";
import { Search, Users } from "lucide-vue-next";

import { computed, ref } from "vue";

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/
type Student = {
    id: number;
    student_number: string;
    fullname: string;
    email: string;
    year_level: string;

    course?: {
        course_name: string;
    };
};

const props = defineProps<{
    assignment: any;
    students: Student[];
}>();

/*
|--------------------------------------------------------------------------
| Search + Filters
|--------------------------------------------------------------------------
*/
const search = ref("");
const selectedYear = ref("");

const years = computed(() => {
    return [...new Set(props.students.map((s) => s.year_level))];
});

/*
|--------------------------------------------------------------------------
| FILTERED STUDENTS
|--------------------------------------------------------------------------
*/
const filteredStudents = computed(() => {
    return props.students.filter((student) => {
        const q = search.value.toLowerCase();

        const matchesSearch =
            student.fullname.toLowerCase().includes(q) ||
            student.student_number.toLowerCase().includes(q) ||
            student.email.toLowerCase().includes(q);

        const matchesYear =
            !selectedYear.value || student.year_level === selectedYear.value;

        return matchesSearch && matchesYear;
    });
});

/*
|--------------------------------------------------------------------------
| TOTAL
|--------------------------------------------------------------------------
*/
const totalStudents = computed(() => filteredStudents.value.length);
</script>

<template>
    <Head title="Take Attendance" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <!-- CLASS HEADER -->
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>
                                {{ assignment.course.course_name }} -
                                {{ assignment.section.section_name }}
                            </CardTitle>

                            <CardDescription>
                                {{ assignment.subject.subject_name }}
                            </CardDescription>
                        </div>

                        <div class="text-sm text-muted-foreground">
                            Year Level:
                            {{ assignment.section.year_level }}
                        </div>
                    </div>
                </CardHeader>
            </Card>

            <!-- STUDENTS CARD -->
            <Card>
                <!-- HEADER + FILTERS -->
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>Students</CardTitle>
                            <CardDescription>
                                Mark attendance for today
                            </CardDescription>
                        </div>

                        <!-- TOTAL -->
                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Users class="w-4 h-4" />
                            <span>Total: {{ totalStudents }}</span>
                        </div>
                    </div>

                    <!-- SEARCH + FILTER -->
                    <div class="flex gap-4 mt-4">
                        <!-- SEARCH -->
                        <div class="relative w-full max-w-sm">
                            <Search
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
                            />

                            <Input
                                v-model="search"
                                placeholder="Search student..."
                                class="pl-10"
                            />
                        </div>

                        <!-- YEAR FILTER -->
                        <select
                            v-model="selectedYear"
                            class="border px-3 py-2 rounded-md w-40"
                        >
                            <option value="">All Year Levels</option>
                            <option v-for="y in years" :key="y" :value="y">
                                {{ y }}
                            </option>
                        </select>
                    </div>
                </CardHeader>

                <CardContent>
                    <!-- TABLE -->
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Student #</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Year Level</TableHead>
                                <TableHead class="text-right">
                                    Action
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="student in filteredStudents"
                                :key="student.id"
                            >
                                <TableCell>
                                    {{ student.student_number }}
                                </TableCell>

                                <TableCell class="font-medium">
                                    {{ student.fullname }}
                                </TableCell>

                                <TableCell>
                                    {{ student.email }}
                                </TableCell>

                                <TableCell>
                                    {{ student.year_level || "N/A" }}
                                </TableCell>

                                <TableCell class="text-right space-x-4">
                                    <div class="flex gap-2 justify-end">
                                        <Button
                                            size="sm"
                                            class="bg-red-600 text-white"
                                        >
                                            Late
                                        </Button>
                                        <Button size="sm" class="bg-yellow-500">
                                            Present
                                        </Button>

                                        <Button
                                            size="sm"
                                            class="bg-red-600 text-white"
                                        >
                                            Absent
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredStudents.length === 0">
                                <TableCell
                                    colspan="5"
                                    class="text-center py-8 text-muted-foreground"
                                >
                                    No students found
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
