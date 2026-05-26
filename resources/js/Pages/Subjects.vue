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

type Teacher = {
    id: number;
    fullname: string;
};
type Subject = {
    teacher: any;
    id: number;
    subject_code: string;
    subject_name: string;
};

const props = defineProps<{
    subjects: Subject[];
    teachers: Teacher[];
}>();

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

const search = ref("");

const filteredSubjects = computed(() => {
    return props.subjects.filter(
        (subject) =>
            subject.subject_code
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            subject.subject_name
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
    subject_code: "",
    subject_name: "",
    teacher_id: "",
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
                    <!-- SEARCH + ADD -->
                    <div class="flex justify-between items-center">
                        <!-- Search -->
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
                                    <DialogTitle>Add Subject</DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                >
                                    <Input
                                        v-model="form.subject_code"
                                        placeholder="Subject Code (e.g. IT101)"
                                    />

                                    <Input
                                        v-model="form.subject_name"
                                        placeholder="Subject Name"
                                    />
                                    <!-- 👇 TEACHER SELECT -->
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
                                    <TableCell>
                                        {{
                                            subject.teacher
                                                ? subject.teacher.fullname
                                                : "No teacher assigned"
                                        }}
                                    </TableCell>
                                </TableRow>

                                <TableRow v-if="filteredSubjects.length === 0">
                                    <TableCell
                                        colspan="2"
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
