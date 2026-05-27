<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

import { Head } from "@inertiajs/vue3";

import { Input } from "@/Components/ui/input";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { Users, Search } from "lucide-vue-next";
import { computed, ref } from "vue";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/
type Course = {
    id: number;
    course_code: string;
    course_name: string;
    department_code: string;
    department_name: string;
};

type Teacher = {
    id: number;
    employee_number: string;
    fullname: string;
    email: string;
    position: string;
    course: Course | null;
};

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    teachers: Teacher[];
    courses: Course[];
    positions: string[];
}>();

/*
|--------------------------------------------------------------------------
| Search / Filter
|--------------------------------------------------------------------------
*/
const search = ref("");
const selectedCourse = ref("");

const courseOptions = computed(() => {
    return [
        ...new Set(
            props.teachers.map((t) => t.course?.course_code).filter(Boolean),
        ),
    ];
});

/*
|--------------------------------------------------------------------------
| Filtered Teachers
|--------------------------------------------------------------------------
*/
const filteredTeachers = computed(() => {
    return props.teachers.filter((teacher) => {
        const q = search.value.toLowerCase();

        const matchesSearch =
            teacher.fullname.toLowerCase().includes(q) ||
            teacher.employee_number.toLowerCase().includes(q) ||
            teacher.email.toLowerCase().includes(q);

        const matchesCourse =
            !selectedCourse.value ||
            teacher.course?.course_code === selectedCourse.value;

        return matchesSearch && matchesCourse;
    });
});

/*
|--------------------------------------------------------------------------
| Stats
|--------------------------------------------------------------------------
*/
const totalTeachers = computed(() => props.teachers.length);
</script>

<template>
    <Head title="Teachers" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>Teacher Records</CardTitle>

                            <CardDescription> Manage teachers </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Users class="w-4 h-4" />

                            <span>Total: {{ totalTeachers }}</span>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="space-y-4">
                    <!-- FILTERS -->
                    <div class="flex justify-between">
                        <div class="flex gap-4">
                            <!-- SEARCH -->
                            <div class="relative w-full max-w-sm">
                                <Search
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4"
                                />

                                <Input
                                    v-model="search"
                                    placeholder="Search..."
                                    class="pl-10"
                                />
                            </div>

                            <!-- COURSE FILTER -->
                            <select
                                v-model="selectedCourse"
                                class="border px-3 py-2 rounded-md w-48"
                            >
                                <option value="">All Courses</option>

                                <option
                                    v-for="c in courseOptions"
                                    :key="c"
                                    :value="c"
                                >
                                    {{ c }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Employee #</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Department</TableHead>
                                <TableHead>Position</TableHead>
                                <TableHead>Email</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="teacher in filteredTeachers"
                                :key="teacher.id"
                            >
                                <TableCell>
                                    {{ teacher.employee_number }}
                                </TableCell>

                                <TableCell>
                                    {{ teacher.fullname }}
                                </TableCell>

                                <TableCell>
                                    {{
                                        teacher.course?.department_code ?? "N/A"
                                    }}
                                </TableCell>

                                <TableCell>
                                    {{ teacher.position }}
                                </TableCell>

                                <TableCell>
                                    {{ teacher.email }}
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredTeachers.length === 0">
                                <TableCell
                                    colspan="5"
                                    class="text-center py-10"
                                >
                                    No teachers found
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
