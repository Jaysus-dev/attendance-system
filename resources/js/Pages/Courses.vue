<script setup lang="ts">
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
| Props
|--------------------------------------------------------------------------
*/

type Course = {
    id: number;
    course_code: string;
    course_name: string;
};

const props = defineProps<{
    courses: Course[];
}>();

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

const search = ref("");

const filteredCourses = computed(() => {
    return props.courses.filter(
        (course) =>
            course.course_code
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            course.course_name
                .toLowerCase()
                .includes(search.value.toLowerCase()),
    );
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
    course_code: "",
    course_name: "",
});

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const submit = () => {
    form.post(route("courses.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            open.value = false;
        },
    });
};
</script>

<template>
    <Head title="Courses" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <!-- HEADER -->
            <Card>
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
                                Total: {{ courses.length }}
                            </span>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="space-y-4">
                    <!-- SEARCH + ADD -->
                    <div class="flex justify-between items-center">
                        <!-- Search -->
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

                        <!-- Add -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button class="gap-2">
                                    <Plus class="w-4 h-4" />
                                    Add Course
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Add Course</DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                >
                                    <Input
                                        v-model="form.course_code"
                                        placeholder="Course Code (e.g. BSIT)"
                                    />

                                    <Input
                                        v-model="form.course_name"
                                        placeholder="Course Name (e.g. BS Information Technology)"
                                    />

                                    <Button type="submit" class="w-full">
                                        Save Course
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
                                    <TableHead>Course Code</TableHead>
                                    <TableHead>Course Name</TableHead>
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <TableRow
                                    v-for="course in filteredCourses"
                                    :key="course.id"
                                >
                                    <TableCell class="font-medium">
                                        {{ course.course_code }}
                                    </TableCell>

                                    <TableCell>
                                        {{ course.course_name }}
                                    </TableCell>
                                </TableRow>

                                <TableRow v-if="filteredCourses.length === 0">
                                    <TableCell
                                        colspan="2"
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
