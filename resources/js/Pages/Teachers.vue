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
| Search
|--------------------------------------------------------------------------
*/

const search = ref("");

const selectedCourse = ref("");

/*
|--------------------------------------------------------------------------
| Course Filter
|--------------------------------------------------------------------------
*/

const courses = computed(() => {
    return [
        ...new Set(props.teachers.map((teacher) => teacher.course.course_code)),
    ];
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
| Statistics
|--------------------------------------------------------------------------
*/

const totalTeachers = computed(() => props.teachers.length);

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
    employee_number: "",

    fullname: "",

    email: "",

    course_id: "",

    department: "",

    position: "",
});

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const submit = () => {
    form.post(route("teachers.store"), {
        preserveScroll: true,

        onSuccess: () => {
            form.reset();

            open.value = false;
        },
    });
};
</script>

<template>
    <Head title="Teachers" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle> Teacher Records </CardTitle>

                            <CardDescription>
                                Search and manage teachers
                            </CardDescription>
                        </div>
                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Users class="w-4 h-4 text-muted-foreground" />
                            <span class="text-sm">
                                Total: {{ totalTeachers }}
                            </span>
                        </div>
                    </div>
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
                        </div>

                        <!-- Add Teacher -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button class="gap-2">
                                    <Plus class="w-4 h-4" />

                                    Add Teacher
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle> Add Teacher </DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
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
                                            {{ course.course_code }}
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
                                        Save Teacher
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
                                    <TableHead> Employee Number </TableHead>

                                    <TableHead> Fullname </TableHead>

                                    <TableHead> Course </TableHead>

                                    <TableHead> Department </TableHead>

                                    <TableHead> Position </TableHead>

                                    <TableHead> Email </TableHead>
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
                                        {{ teacher.course.course_code }}
                                    </TableCell>

                                    <TableCell>
                                        {{ teacher.department }}
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
                                        colspan="6"
                                        class="text-center py-10"
                                    >
                                        No teachers found.
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
