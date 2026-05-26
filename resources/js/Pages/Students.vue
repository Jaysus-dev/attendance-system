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

import { computed, ref, watch } from "vue";

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
};

type Section = {
    id: number;

    course_id: number;

    section_name: string;
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

        course_code: string;

        course_name: string;
    };

    section: {
        id: number;

        course_id: number;

        section_name: string;
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
    return [
        ...new Set(props.students.map((student) => student.course.course_code)),
    ];
});

const sections = computed(() => {
    return [
        ...new Set(
            props.students.map((student) => student.section.section_name),
        ),
    ];
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
            student.course.course_code === selectedCourse.value;

        const matchesSection =
            !selectedSection.value ||
            student.section.section_name === selectedSection.value;

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
| Filter Sections Based On Selected Course
|--------------------------------------------------------------------------
*/

const filteredSections = computed(() => {
    // No course selected
    if (!form.course_id) {
        return [];
    }

    return props.sections.filter((section) => {
        return section.course_id === Number(form.course_id);
    });
});

/*
|--------------------------------------------------------------------------
| Reset Section When Course Changes
|--------------------------------------------------------------------------
*/

watch(
    () => form.course_id,

    () => {
        form.section_id = "";
    },
);

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
        <div class="p-4 space-y-6">
            <!-- Main Card -->
            <Card>
                <!-- Header -->
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <!-- Left -->
                        <div>
                            <CardTitle> Student Records </CardTitle>

                            <CardDescription>
                                Search and filter students
                            </CardDescription>
                        </div>

                        <!-- Right -->

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Users class="w-4 h-4 text-muted-foreground" />
                            <span class="text-sm">
                                Total: {{ totalStudents }}
                            </span>
                        </div>
                    </div>
                </CardHeader>

                <!-- Content -->
                <CardContent class="space-y-4">
                    <!-- Filters -->
                    <div class="flex justify-between">
                        <!-- Left -->
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

                            <!-- Course Filter -->
                            <select
                                v-model="selectedCourse"
                                class="border rounded-md px-3 py-2 w-full max-w-40"
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

                            <!-- Section Filter -->
                            <select
                                v-model="selectedSection"
                                class="border rounded-md px-3 py-2 w-full max-w-40"
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
                                <Button class="gap-2">
                                    <Plus class="w-4 h-4" />

                                    Add Student
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle> Add Student </DialogTitle>
                                </DialogHeader>

                                <!-- Form -->
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
                                            {{ course.course_name }}
                                        </option>
                                    </select>

                                    <!-- Section -->
                                    <select
                                        v-model="form.section_id"
                                        class="w-full border rounded-md px-3 py-2"
                                    >
                                        <option value="">Select Section</option>

                                        <option
                                            v-for="section in filteredSections"
                                            :key="section.id"
                                            :value="section.id"
                                        >
                                            {{ section.section_name }}
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
                                        {{ student.course.course_code }}
                                    </TableCell>

                                    <TableCell>
                                        {{ student.section.section_name }}
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

                                <!-- Empty -->
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
