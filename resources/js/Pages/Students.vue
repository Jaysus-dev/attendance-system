<script setup lang="ts">
/*
|--------------------------------------------------------------------------
| Imports
|--------------------------------------------------------------------------
*/

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";

import { Head, useForm } from "@inertiajs/vue3";

import { Button } from "@/Components/ui/button";

import { Input } from "@/Components/ui/input";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { Users, Search, Plus } from "lucide-vue-next";

import { computed, ref } from "vue";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

type Course = {
    id: number;
    name: string;
};

type Section = {
    id: number;
    name: string;
};

type Student = {
    id: number;

    student_number: string;

    fullname: string;

    email: string;

    parent_email: string;

    year_level: string;

    course: {
        id: number;
        name: string;
    };

    section: {
        id: number;
        name: string;
    };
};

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = defineProps<{
    students: Student[];
    courses: Course[];
    sections: Section[];
}>();

/*
|--------------------------------------------------------------------------
| Search & Filters
|--------------------------------------------------------------------------
*/

const search = ref("");

const selectedCourse = ref("");

const selectedSection = ref("");

/*
|--------------------------------------------------------------------------
| Dynamic Filter Options
|--------------------------------------------------------------------------
*/

const courses = computed(() => {
    return [...new Set(props.students.map((student) => student.course.name))];
});

const sections = computed(() => {
    return [...new Set(props.students.map((student) => student.section.name))];
});

/*
|--------------------------------------------------------------------------
| Filtered Students
|--------------------------------------------------------------------------
*/

const filteredStudents = computed(() => {
    return props.students.filter((student) => {
        const matchesSearch =
            student.fullname
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            student.student_number
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            student.email.toLowerCase().includes(search.value.toLowerCase());

        const matchesCourse =
            !selectedCourse.value ||
            student.course.name === selectedCourse.value;

        const matchesSection =
            !selectedSection.value ||
            student.section.name === selectedSection.value;

        return matchesSearch && matchesCourse && matchesSection;
    });
});

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

const totalStudents = computed(() => props.students.length);

/*
|--------------------------------------------------------------------------
| Modal
|--------------------------------------------------------------------------
*/

const open = ref(false);

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = useForm({
    student_number: "",

    fullname: "",

    email: "",

    parent_email: "",

    course_id: "",

    section_id: "",

    year_level: "",
});

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const submit = () => {
    form.post(route("students.store"), {
        preserveScroll: true,

        onSuccess: () => {
            form.reset();

            open.value = false;
        },
    });
};
</script>

<template>
    <Head title="Students" />

    <AuthenticatedLayout>
        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Students</h1>

                    <p class="text-muted-foreground">Manage students</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle class="text-sm"> Total Students </CardTitle>

                        <Users class="w-4 h-4" />
                    </CardHeader>

                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ totalStudents }}
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Records -->
            <Card>
                <CardHeader>
                    <CardTitle> Student Records </CardTitle>

                    <CardDescription>
                        Search and filter students
                    </CardDescription>
                </CardHeader>

                <CardContent class="space-y-4">
                    <!-- Filters -->
                    <div class="flex justify-between">
                        <div class="flex gap-4 w-full">
                            <!-- Search -->
                            <div class="relative w-full max-w-sm">
                                <Search
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
                                />

                                <Input
                                    v-model="search"
                                    placeholder="Search..."
                                    class="pl-10"
                                />
                            </div>

                            <!-- Course -->
                            <select
                                v-model="selectedCourse"
                                class="border rounded-md px-3 py-2"
                            >
                                <option value="">All Courses</option>

                                <option
                                    v-for="course in courses"
                                    :key="course"
                                    :value="course"
                                >
                                    {{ course }}
                                </option>
                            </select>

                            <!-- Section -->
                            <select
                                v-model="selectedSection"
                                class="border rounded-md px-3 py-2"
                            >
                                <option value="">All Sections</option>

                                <option
                                    v-for="section in sections"
                                    :key="section"
                                    :value="section"
                                >
                                    {{ section }}
                                </option>
                            </select>
                        </div>
                        <!-- Add Student -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button variant="destructive" class="gap-2">
                                    <Plus class="w-4 h-4" />

                                    Add Student
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle> Add Student </DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                >
                                    <!-- Student Number -->
                                    <Input
                                        v-model="form.student_number"
                                        placeholder="Student Number"
                                    />

                                    <!-- Fullname -->
                                    <Input
                                        v-model="form.fullname"
                                        placeholder="Fullname"
                                    />

                                    <!-- Email -->
                                    <Input
                                        v-model="form.email"
                                        placeholder="Email"
                                    />

                                    <!-- Parent Email -->
                                    <Input
                                        v-model="form.parent_email"
                                        placeholder="Parent Email"
                                    />

                                    <!-- Course -->
                                    <select
                                        v-model="form.course_id"
                                        class="w-full border rounded-md px-3 py-2"
                                    >
                                        <option value="">Select Course</option>

                                        <option
                                            v-for="course in props.courses"
                                            :key="course.id"
                                            :value="course.id"
                                        >
                                            {{ course.name }}
                                        </option>
                                    </select>

                                    <!-- Section -->
                                    <select
                                        v-model="form.section_id"
                                        class="w-full border rounded-md px-3 py-2"
                                    >
                                        <option value="">Select Section</option>

                                        <option
                                            v-for="section in props.sections"
                                            :key="section.id"
                                            :value="section.id"
                                        >
                                            {{ section.name }}
                                        </option>
                                    </select>

                                    <!-- Year Level -->
                                    <select
                                        v-model="form.year_level"
                                        class="w-full border rounded-md px-3 py-2"
                                    >
                                        <option value="">
                                            Select Year Level
                                        </option>

                                        <option value="1st Year">
                                            1st Year
                                        </option>

                                        <option value="2nd Year">
                                            2nd Year
                                        </option>

                                        <option value="3rd Year">
                                            3rd Year
                                        </option>

                                        <option value="4th Year">
                                            4th Year
                                        </option>
                                    </select>

                                    <!-- Submit -->
                                    <Button type="submit" class="w-full">
                                        Save Student
                                    </Button>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>

                    <!-- Table -->
                    <div class="rounded-md border overflow-hidden">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead> Student Number </TableHead>

                                    <TableHead> Fullname </TableHead>

                                    <TableHead> Course </TableHead>

                                    <TableHead> Section </TableHead>

                                    <TableHead> Year Level </TableHead>

                                    <TableHead> Email </TableHead>

                                    <TableHead> Parent Email </TableHead>
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

                                    <TableCell>
                                        {{ student.fullname }}
                                    </TableCell>

                                    <TableCell>
                                        {{ student.course.name }}
                                    </TableCell>

                                    <TableCell>
                                        {{ student.section.name }}
                                    </TableCell>

                                    <TableCell>
                                        {{ student.year_level }}
                                    </TableCell>

                                    <TableCell>
                                        {{ student.email }}
                                    </TableCell>

                                    <TableCell>
                                        {{ student.parent_email }}
                                    </TableCell>
                                </TableRow>

                                <TableRow v-if="filteredStudents.length === 0">
                                    <TableCell
                                        colspan="7"
                                        class="text-center py-10"
                                    >
                                        No students found.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
