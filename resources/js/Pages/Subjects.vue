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
| Course Filter Options (NO DUPLICATION SAFE)
|--------------------------------------------------------------------------
*/
const courses = computed(() => {
    return [
        ...new Set(props.subjects.map((s) => s.course?.course_code)),
    ].filter(Boolean);
});

/*
|--------------------------------------------------------------------------
| Filtered Subjects
|--------------------------------------------------------------------------
*/
const filteredSubjects = computed(() => {
    return props.subjects.filter((subject) => {
        const q = search.value.toLowerCase();

        const matchesSearch =
            subject.subject_code.toLowerCase().includes(q) ||
            subject.subject_name.toLowerCase().includes(q);

        const matchesCourse =
            !selectedCourse.value ||
            subject.course?.course_code === selectedCourse.value;

        return matchesSearch && matchesCourse;
    });
});

/*
|--------------------------------------------------------------------------
| Modal State
|--------------------------------------------------------------------------
*/
const open = ref(false);
const editMode = ref(false);
const selectedSubjectId = ref<number | null>(null);

/*
|--------------------------------------------------------------------------
| FORM
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
| RESET FORM
|--------------------------------------------------------------------------
*/
const resetForm = () => {
    form.clearErrors();

    form.subject_code = "";
    form.subject_name = "";
    form.teacher_id = "";
    form.course_id = "";

    editMode.value = false;
    selectedSubjectId.value = null;
};

/*
|--------------------------------------------------------------------------
| EDIT SUBJECT
|--------------------------------------------------------------------------
*/
const editSubject = (subject: Subject) => {
    editMode.value = true;
    selectedSubjectId.value = subject.id;
    open.value = true;

    form.subject_code = subject.subject_code;
    form.subject_name = subject.subject_name;
    form.teacher_id = subject.teacher?.id ? String(subject.teacher.id) : "";
    form.course_id = subject.course?.id ? String(subject.course.id) : "";
};

/*
|--------------------------------------------------------------------------
| DELETE SUBJECT
|--------------------------------------------------------------------------
*/
const deleteSubject = (id: number) => {
    if (confirm("Are you sure you want to delete this subject?")) {
        form.delete(route("subjects.destroy", id), {
            preserveScroll: true,
        });
    }
};

/*
|--------------------------------------------------------------------------
| SUBMIT (CREATE / UPDATE)
|--------------------------------------------------------------------------
*/
const submit = () => {
    if (editMode.value && selectedSubjectId.value) {
        form.put(route("subjects.update", selectedSubjectId.value), {
            preserveScroll: true,
            onSuccess: () => {
                resetForm();
                open.value = false;
            },
        });
    } else {
        form.post(route("subjects.store"), {
            preserveScroll: true,
            onSuccess: () => {
                resetForm();
                open.value = false;
            },
        });
    }
};
</script>

<template>
    <Head title="Subjects" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <!-- HEADER -->
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
                            <span>Total: {{ subjects.length }}</span>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="space-y-4">
                    <!-- SEARCH + FILTER + ADD -->
                    <div class="flex justify-between items-center gap-4">
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
                                    v-for="c in courses"
                                    :key="c"
                                    :value="c"
                                >
                                    {{ c }}
                                </option>
                            </select>
                        </div>

                        <!-- ADD -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button @click="resetForm" class="gap-2">
                                    <Plus class="w-4 h-4" />
                                    Add Subject
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>
                                        {{
                                            editMode
                                                ? "Edit Subject"
                                                : "Add Subject"
                                        }}
                                    </DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                >
                                    <Input
                                        v-model="form.subject_code"
                                        placeholder="Subject Code"
                                    />
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
                                        {{ editMode ? "Update" : "Save" }}
                                    </Button>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>

                    <!-- TABLE -->
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Subject Code</TableHead>
                                <TableHead>Subject Name</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Teacher</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="subject in filteredSubjects"
                                :key="subject.id"
                            >
                                <TableCell>{{
                                    subject.subject_code
                                }}</TableCell>
                                <TableCell>{{
                                    subject.subject_name
                                }}</TableCell>

                                <TableCell>
                                    {{
                                        subject.course?.course_code ??
                                        "No course"
                                    }}
                                </TableCell>

                                <TableCell>
                                    {{
                                        subject.teacher?.fullname ??
                                        "No teacher"
                                    }}
                                </TableCell>

                                <!-- ACTIONS -->
                                <TableCell class="flex gap-2">
                                    <Button
                                        size="sm"
                                        class="bg-yellow-500 text-white"
                                        @click="editSubject(subject)"
                                    >
                                        Edit
                                    </Button>

                                    <Button
                                        size="sm"
                                        class="bg-red-600 text-white"
                                        @click="deleteSubject(subject.id)"
                                    >
                                        Delete
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredSubjects.length === 0">
                                <TableCell
                                    colspan="5"
                                    class="text-center py-10"
                                >
                                    No subjects found.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
