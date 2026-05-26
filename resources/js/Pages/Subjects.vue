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

import { Search, Plus, BookOpen } from "lucide-vue-next";

import { computed, ref } from "vue";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/
type Teacher = {
    id: number;
    fullname: string;
};

type Course = {
    id: number;
    course_code: string;
    course_name: string;
};

type Subject = {
    id: number;
    subject_code: string;
    subject_name: string;

    teacher: Teacher | null;

    course: Course | null;
};

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    subjects: Subject[];
    teachers: Teacher[];
    courses: Course[];
}>();

/*
|--------------------------------------------------------------------------
| Search + Course Filter
|--------------------------------------------------------------------------
*/
const search = ref("");

const selectedCourse = ref("");

/*
|--------------------------------------------------------------------------
| Course Filter Options
|--------------------------------------------------------------------------
*/
const courses = computed(() => [
    ...new Set(
        props.subjects
            .filter((s) => s.course)
            .map((s) => s.course?.course_code),
    ),
]);

/*
|--------------------------------------------------------------------------
| Filtered Subjects
|--------------------------------------------------------------------------
*/
const filteredSubjects = computed(() => {
    return props.subjects.filter((subject) => {
        const matchesSearch =
            subject.subject_code
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            subject.subject_name
                .toLowerCase()
                .includes(search.value.toLowerCase());

        const matchesCourse =
            !selectedCourse.value ||
            subject.course?.course_code === selectedCourse.value;

        return matchesSearch && matchesCourse;
    });
});

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
    subject_code: "",
    subject_name: "",
    teacher_id: "",
    course_id: "",
});

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
const submit = () => {
    form.post(route("subjects.store"), {
        preserveScroll: true,

        onSuccess: () => {
            form.reset();

            open.value = false;
        },
    });
};
</script>

<template>
    <Head title="Subjects" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <!-- HEADER -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Subjects</CardTitle>

                            <CardDescription>
                                Manage all school subjects
                            </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <BookOpen class="w-4 h-4" />

                            <span class="text-sm">
                                Total: {{ subjects.length }}
                            </span>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="space-y-4">
                    <!-- SEARCH + FILTER + ADD -->
                    <div class="flex justify-between items-center gap-4">
                        <!-- SEARCH + FILTER -->
                        <div class="flex gap-4 w-full">
                            <!-- SEARCH -->
                            <div class="relative w-full max-w-sm">
                                <Search
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
                                />

                                <Input
                                    v-model="search"
                                    placeholder="Search subjects..."
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
                                    v-for="course in courses"
                                    :key="course"
                                    :value="course"
                                >
                                    {{ course }}
                                </option>
                            </select>
                        </div>

                        <!-- ADD -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button class="gap-2">
                                    <Plus class="w-4 h-4" />

                                    Add Subject
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle> Add Subject </DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                >
                                    <!-- SUBJECT CODE -->
                                    <Input
                                        v-model="form.subject_code"
                                        placeholder="Subject Code (e.g. IT101)"
                                    />

                                    <!-- SUBJECT NAME -->
                                    <Input
                                        v-model="form.subject_name"
                                        placeholder="Subject Name"
                                    />

                                    <!-- COURSE -->
                                    <select
                                        v-model="form.course_id"
                                        class="w-full border px-3 py-2 rounded-md"
                                    >
                                        <option value="">Select Course</option>

                                        <option
                                            v-for="course in props.courses"
                                            :key="course.id"
                                            :value="course.id"
                                        >
                                            {{ course.course_code }}
                                        </option>
                                    </select>

                                    <!-- TEACHER -->
                                    <select
                                        v-model="form.teacher_id"
                                        class="w-full border px-3 py-2 rounded-md"
                                    >
                                        <option value="">Select Teacher</option>

                                        <option
                                            v-for="t in props.teachers"
                                            :key="t.id"
                                            :value="t.id"
                                        >
                                            {{ t.fullname }}
                                        </option>
                                    </select>

                                    <Button type="submit" class="w-full">
                                        Save Subject
                                    </Button>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>

                    <!-- TABLE -->
                    <div class="rounded-md border overflow-hidden">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Subject Code</TableHead>

                                    <TableHead>Subject Name</TableHead>

                                    <TableHead>Course</TableHead>

                                    <TableHead>Teacher</TableHead>
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <TableRow
                                    v-for="subject in filteredSubjects"
                                    :key="subject.id"
                                >
                                    <TableCell class="font-medium">
                                        {{ subject.subject_code }}
                                    </TableCell>

                                    <TableCell>
                                        {{ subject.subject_name }}
                                    </TableCell>

                                    <!-- COURSE -->
                                    <TableCell>
                                        {{
                                            subject.course
                                                ? subject.course.course_code
                                                : "No course assigned"
                                        }}
                                    </TableCell>

                                    <!-- TEACHER -->
                                    <TableCell>
                                        {{
                                            subject.teacher
                                                ? subject.teacher.fullname
                                                : "No teacher assigned"
                                        }}
                                    </TableCell>
                                </TableRow>

                                <!-- EMPTY -->
                                <TableRow v-if="filteredSubjects.length === 0">
                                    <TableCell
                                        colspan="4"
                                        class="text-center py-10"
                                    >
                                        No subjects found.
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
