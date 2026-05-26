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
type Course = {
    id: number;
    department_code: string;
    department_name: string;
    course_code: string;
    course_name: string;
};

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    courses: Course[];
}>();

/*
|--------------------------------------------------------------------------
| Search + Department Filter
|--------------------------------------------------------------------------
*/
const search = ref("");

const selectedDepartment = ref("");

const departments = computed(() => [
    ...new Set(props.courses.map((c) => c.department_code)),
]);

/*
|--------------------------------------------------------------------------
| Filtered Courses
|--------------------------------------------------------------------------
*/
const filteredCourses = computed(() => {
    return props.courses.filter((course) => {
        const matchesSearch =
            course.course_code
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            course.course_name
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            course.department_code
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            course.department_name
                .toLowerCase()
                .includes(search.value.toLowerCase());

        const matchesDepartment =
            !selectedDepartment.value ||
            course.department_code === selectedDepartment.value;

        return matchesSearch && matchesDepartment;
    });
});

/*
|--------------------------------------------------------------------------
| Dialog State
|--------------------------------------------------------------------------
*/
const open = ref(false);

const editMode = ref(false);

const selectedCourseId = ref<number | null>(null);

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    department_code: "",
    department_name: "",
    course_code: "",
    course_name: "",
});

/*
|--------------------------------------------------------------------------
| Reset Form
|--------------------------------------------------------------------------
*/
const resetForm = () => {
    form.clearErrors();

    form.department_code = "";
    form.department_name = "";
    form.course_code = "";
    form.course_name = "";

    editMode.value = false;
    selectedCourseId.value = null;
};

/*
|--------------------------------------------------------------------------
| Edit Course
|--------------------------------------------------------------------------
*/
const editCourse = (course: Course) => {
    editMode.value = true;

    selectedCourseId.value = course.id;

    open.value = true;

    form.department_code = course.department_code;
    form.department_name = course.department_name;
    form.course_code = course.course_code;
    form.course_name = course.course_name;
};

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
const submit = () => {
    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    if (editMode.value && selectedCourseId.value) {
        form.put(route("courses.update", selectedCourseId.value), {
            preserveScroll: true,

            onSuccess: () => {
                resetForm();
                open.value = false;
            },
        });

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    form.post(route("courses.store"), {
        preserveScroll: true,

        onSuccess: () => {
            resetForm();
            open.value = false;
        },
    });
};

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/
const deleteCourse = (id: number) => {
    if (confirm("Are you sure you want to delete this course?")) {
        form.delete(route("courses.destroy", id), {
            preserveScroll: true,
        });
    }
};

/*
|--------------------------------------------------------------------------
| Stats
|--------------------------------------------------------------------------
*/
const totalCourses = computed(() => props.courses.length);
</script>

<template>
    <Head title="Courses" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <!-- HEADER -->
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Courses</CardTitle>

                            <CardDescription>
                                Manage all school courses
                            </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <BookOpen class="w-4 h-4" />

                            <span class="text-sm">
                                Total: {{ totalCourses }}
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
                                    placeholder="Search courses..."
                                    class="pl-10"
                                />
                            </div>

                            <!-- DEPARTMENT FILTER -->
                            <select
                                v-model="selectedDepartment"
                                class="border px-3 py-2 rounded-md w-52"
                            >
                                <option value="">All Departments</option>

                                <option
                                    v-for="department in departments"
                                    :key="department"
                                    :value="department"
                                >
                                    {{ department }}
                                </option>
                            </select>
                        </div>

                        <!-- ADD / EDIT -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button @click="resetForm">
                                    <Plus class="w-4 h-4 mr-2" />

                                    Add Course
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>
                                        {{
                                            editMode
                                                ? "Edit Course"
                                                : "Add Course"
                                        }}
                                    </DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                >
                                    <!-- Department -->
                                    <Input
                                        v-model="form.department_code"
                                        placeholder="Department Code (e.g. CCS)"
                                    />

                                    <Input
                                        v-model="form.department_name"
                                        placeholder="Department Name"
                                    />

                                    <!-- Course -->
                                    <Input
                                        v-model="form.course_code"
                                        placeholder="Course Code (e.g. BSIT)"
                                    />

                                    <Input
                                        v-model="form.course_name"
                                        placeholder="Course Name"
                                    />

                                    <Button type="submit" class="w-full">
                                        {{ editMode ? "Update" : "Save" }}
                                    </Button>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>

                    <!-- TABLE -->
                    <div class="overflow-hidden">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Department</TableHead>

                                    <TableHead>Course Code</TableHead>

                                    <TableHead>Course Name</TableHead>

                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <TableRow
                                    v-for="course in filteredCourses"
                                    :key="course.id"
                                >
                                    <TableCell>
                                        {{ course.department_name }}
                                        -
                                        {{ course.department_code }}
                                    </TableCell>

                                    <TableCell class="font-medium">
                                        {{ course.course_code }}
                                    </TableCell>

                                    <TableCell>
                                        {{ course.course_name }}
                                    </TableCell>

                                    <!-- ACTIONS -->
                                    <TableCell class="flex gap-2">
                                        <Button
                                            class="bg-yellow-500 text-white"
                                            size="sm"
                                            @click="editCourse(course)"
                                        >
                                            Edit
                                        </Button>

                                        <Button
                                            class="bg-red-600 text-white"
                                            size="sm"
                                            @click="deleteCourse(course.id)"
                                        >
                                            Delete
                                        </Button>
                                    </TableCell>
                                </TableRow>

                                <!-- EMPTY -->
                                <TableRow v-if="filteredCourses.length === 0">
                                    <TableCell
                                        colspan="4"
                                        class="text-center py-10"
                                    >
                                        No courses found.
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
