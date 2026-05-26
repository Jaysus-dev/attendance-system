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
    return [...new Set(props.students.map((s) => s.course.course_code))];
});

const sections = computed(() => {
    return [...new Set(props.students.map((s) => s.section.section_name))];
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
| Stats
|--------------------------------------------------------------------------
*/

const totalStudents = computed(() => props.students.length);

/*
|--------------------------------------------------------------------------
| Dialog State
|--------------------------------------------------------------------------
*/

const open = ref(false);
const editMode = ref(false);
const selectedStudentId = ref<number | null>(null);

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
| Reset Form
|--------------------------------------------------------------------------
*/

const resetForm = () => {
    form.reset();
    editMode.value = false;
    selectedStudentId.value = null;
};

/*
|--------------------------------------------------------------------------
| Filter Sections based on Course
|--------------------------------------------------------------------------
*/

const filteredSections = computed(() => {
    if (!form.course_id) return [];

    return props.sections.filter(
        (section) => section.course_id === Number(form.course_id),
    );
});

/*
|--------------------------------------------------------------------------
| Watch Course Change
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
| EDIT STUDENT
|--------------------------------------------------------------------------
*/

const editStudent = (student: Student) => {
    editMode.value = true;
    open.value = true;

    selectedStudentId.value = student.id;

    form.student_number = student.student_number;
    form.fullname = student.fullname;
    form.email = student.email;
    form.parent_email = student.parent_email;
    form.course_id = String(student.course.id);
    form.section_id = String(student.section.id);
    form.year_level = student.year_level;
};

/*
|--------------------------------------------------------------------------
| DELETE STUDENT
|--------------------------------------------------------------------------
*/

const deleteStudent = (id: number) => {
    if (confirm("Are you sure you want to delete this student?")) {
        form.delete(route("students.destroy", id), {
            preserveScroll: true,
        });
    }
};

/*
|--------------------------------------------------------------------------
| SUBMIT (ADD / UPDATE)
|--------------------------------------------------------------------------
*/

const submit = () => {
    if (editMode.value && selectedStudentId.value) {
        form.put(route("students.update", selectedStudentId.value), {
            preserveScroll: true,
            onSuccess: () => {
                resetForm();
                open.value = false;
            },
        });
    } else {
        form.post(route("students.store"), {
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
    <Head title="Students" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <!-- CARD -->
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>Student Records</CardTitle>
                            <CardDescription>Manage students</CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Users class="w-4 h-4" />
                            <span>Total: {{ totalStudents }}</span>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="space-y-4">
                    <!-- FILTERS -->
                    <div class="flex justify-between gap-4">
                        <div class="flex gap-4 w-full">
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

                            <select
                                v-model="selectedCourse"
                                class="border px-3 py-2 rounded-md"
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

                            <select
                                v-model="selectedSection"
                                class="border px-3 py-2 rounded-md"
                            >
                                <option value="">All Sections</option>
                                <option
                                    v-for="s in sections"
                                    :key="s"
                                    :value="s"
                                >
                                    {{ s }}
                                </option>
                            </select>
                        </div>

                        <!-- ADD BUTTON -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button @click="resetForm">
                                    <Plus class="w-4 h-4 mr-2" />
                                    Enroll Student
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>
                                        {{
                                            editMode
                                                ? "Edit Student"
                                                : "Add Student"
                                        }}
                                    </DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-3"
                                >
                                    <Input
                                        v-model="form.student_number"
                                        placeholder="Student Number"
                                    />
                                    <Input
                                        v-model="form.fullname"
                                        placeholder="Fullname"
                                    />
                                    <Input
                                        v-model="form.email"
                                        placeholder="Email"
                                    />
                                    <Input
                                        v-model="form.parent_email"
                                        placeholder="Parent Email"
                                    />

                                    <select
                                        v-model="form.course_id"
                                        class="w-full border px-3 py-2 rounded-md"
                                    >
                                        <option value="">Select Course</option>
                                        <option
                                            v-for="c in props.courses"
                                            :key="c.id"
                                            :value="c.id"
                                        >
                                            {{ c.course_name }}
                                        </option>
                                    </select>

                                    <select
                                        v-model="form.section_id"
                                        class="w-full border px-3 py-2 rounded-md"
                                    >
                                        <option value="">Select Section</option>
                                        <option
                                            v-for="s in filteredSections"
                                            :key="s.id"
                                            :value="s.id"
                                        >
                                            {{ s.section_name }}
                                        </option>
                                    </select>

                                    <select
                                        v-model="form.year_level"
                                        class="w-full border px-3 py-2 rounded-md"
                                    >
                                        <option value="">Year Level</option>
                                        <option>1st Year</option>
                                        <option>2nd Year</option>
                                        <option>3rd Year</option>
                                        <option>4th Year</option>
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
                                <TableHead>Student #</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Section</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Actions</TableHead>
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
                                <TableCell>{{
                                    student.course.course_code
                                }}</TableCell>
                                <TableCell>{{
                                    student.section.section_name
                                }}</TableCell>
                                <TableCell>{{ student.email }}</TableCell>

                                <TableCell class="flex gap-2">
                                    <Button
                                        size="sm"
                                        @click="editStudent(student)"
                                    >
                                        Edit
                                    </Button>

                                    <Button
                                        size="sm"
                                        variant="destructive"
                                        @click="deleteStudent(student.id)"
                                    >
                                        Delete
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
