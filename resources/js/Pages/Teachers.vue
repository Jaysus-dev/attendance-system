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
    course_code: string;
    course_name: string;
};

type Teacher = {
    id: number;
    employee_number: string;
    fullname: string;
    email: string;
    department: string;
    position: string;

    course: {
        id: number;
        course_code: string;
        course_name: string;
    };
};

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    teachers: Teacher[];
    courses: Course[];
}>();

/*
|--------------------------------------------------------------------------
| Search / Filter
|--------------------------------------------------------------------------
*/
const search = ref("");
const selectedCourse = ref("");

const courses = computed(() => {
    return [...new Set(props.teachers.map((t) => t.course.course_code))];
});

/*
|--------------------------------------------------------------------------
| Filtered Teachers
|--------------------------------------------------------------------------
*/
const filteredTeachers = computed(() => {
    return props.teachers.filter((teacher) => {
        const matchesSearch =
            teacher.fullname
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            teacher.employee_number
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            teacher.email.toLowerCase().includes(search.value.toLowerCase());

        const matchesCourse =
            !selectedCourse.value ||
            teacher.course.course_code === selectedCourse.value;

        return matchesSearch && matchesCourse;
    });
});

/*
|--------------------------------------------------------------------------
| Stats
|--------------------------------------------------------------------------
*/
const totalTeachers = computed(() => props.teachers.length);

/*
|--------------------------------------------------------------------------
| Dialog + Edit State
|--------------------------------------------------------------------------
*/
const open = ref(false);
const editMode = ref(false);
const selectedTeacherId = ref<number | null>(null);

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    employee_number: "",
    fullname: "",
    email: "",
    course_id: "",
    department: "",
    position: "",
});

/*
|--------------------------------------------------------------------------
| Reset
|--------------------------------------------------------------------------
*/
const resetForm = () => {
    form.reset();
    editMode.value = false;
    selectedTeacherId.value = null;
};

/*
|--------------------------------------------------------------------------
| Edit Teacher
|--------------------------------------------------------------------------
*/
const editTeacher = (teacher: Teacher) => {
    editMode.value = true;
    open.value = true;
    selectedTeacherId.value = teacher.id;

    form.employee_number = teacher.employee_number;
    form.fullname = teacher.fullname;
    form.email = teacher.email;
    form.course_id = String(teacher.course.id);
    form.department = teacher.department;
    form.position = teacher.position;
};

/*
|--------------------------------------------------------------------------
| Delete Teacher
|--------------------------------------------------------------------------
*/
const deleteTeacher = (id: number) => {
    if (confirm("Are you sure you want to delete this teacher?")) {
        form.delete(route("teachers.destroy", id), {
            preserveScroll: true,
        });
    }
};

/*
|--------------------------------------------------------------------------
| Submit (Add / Update)
|--------------------------------------------------------------------------
*/
const submit = () => {
    if (editMode.value && selectedTeacherId.value) {
        form.put(route("teachers.update", selectedTeacherId.value), {
            preserveScroll: true,
            onSuccess: () => {
                resetForm();
                open.value = false;
            },
        });
    } else {
        form.post(route("teachers.store"), {
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
    <Head title="Teachers" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>Teacher Records</CardTitle>
                            <CardDescription>Manage teachers</CardDescription>
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
                                class="border px-3 py-2 rounded-md w-40"
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

                        <!-- ADD / EDIT -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button @click="resetForm">
                                    <Plus class="w-4 h-4 mr-2" />
                                    Add Teacher
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>
                                        {{
                                            editMode
                                                ? "Edit Teacher"
                                                : "Add Teacher"
                                        }}
                                    </DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-3"
                                >
                                    <Input
                                        v-model="form.employee_number"
                                        placeholder="Employee Number"
                                    />
                                    <Input
                                        v-model="form.fullname"
                                        placeholder="Fullname"
                                    />
                                    <Input
                                        v-model="form.email"
                                        placeholder="Email"
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
                                            {{ c.course_code }}
                                        </option>
                                    </select>

                                    <Input
                                        v-model="form.department"
                                        placeholder="Department"
                                    />
                                    <Input
                                        v-model="form.position"
                                        placeholder="Position"
                                    />

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
                                <TableHead>Employee #</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Department</TableHead>
                                <TableHead>Position</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="teacher in filteredTeachers"
                                :key="teacher.id"
                            >
                                <TableCell>{{
                                    teacher.employee_number
                                }}</TableCell>
                                <TableCell>{{ teacher.fullname }}</TableCell>
                                <TableCell>{{
                                    teacher.course.course_code
                                }}</TableCell>
                                <TableCell>{{ teacher.department }}</TableCell>
                                <TableCell>{{ teacher.position }}</TableCell>
                                <TableCell>{{ teacher.email }}</TableCell>

                                <TableCell class="flex gap-2">
                                    <!-- EDIT -->
                                    <Button
                                        class="bg-yellow-500 text-white hover:bg-yellow-600"
                                        size="sm"
                                        @click="editTeacher(teacher)"
                                    >
                                        Edit
                                    </Button>

                                    <!-- DELETE -->
                                    <Button
                                        class="bg-red-600 text-white hover:bg-red-700"
                                        size="sm"
                                        @click="deleteTeacher(teacher.id)"
                                    >
                                        Delete
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredTeachers.length === 0">
                                <TableCell
                                    colspan="7"
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
