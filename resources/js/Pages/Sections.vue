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

import { Search, Plus, Layers } from "lucide-vue-next";
import { computed, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

type Course = {
    id: number;
    course_name: string;
};

type Section = {
    id: number;
    section_name: string;
    course: Course;
};

const props = defineProps<{
    sections: Section[];
    courses: Course[];
}>();

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

const search = ref("");

const filteredSections = computed(() => {
    return props.sections.filter(
        (section) =>
            section.section_name
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            section.course.course_name
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
    course_id: "",
    section_name: "",
});

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const submit = () => {
    form.post(route("sections.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            open.value = false;
        },
    });
};
</script>

<template>
    <Head title="Sections" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle>Sections</CardTitle>
                            <CardDescription
                                >Manage all sections</CardDescription
                            >
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <Layers class="w-4 h-4" />
                            <span>Total: {{ sections.length }}</span>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="space-y-4">
                    <!-- Search + Add -->
                    <div class="flex justify-between items-center">
                        <div class="relative w-full max-w-sm">
                            <Search
                                class="absolute left-3 top-2.5 w-4 h-4 text-muted-foreground"
                            />

                            <Input
                                v-model="search"
                                placeholder="Search sections..."
                                class="pl-10"
                            />
                        </div>

                        <!-- ADD -->
                        <Dialog v-model:open="open">
                            <DialogTrigger as-child>
                                <Button class="gap-2">
                                    <Plus class="w-4 h-4" />
                                    Add Section
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Add Section</DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                >
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

                                    <Input
                                        v-model="form.section_name"
                                        placeholder="Section Name (e.g. BSIT-1A)"
                                    />

                                    <Button type="submit" class="w-full">
                                        Save Section
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
                                    <TableHead>Course</TableHead>
                                    <TableHead>Section Name</TableHead>
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <TableRow
                                    v-for="section in filteredSections"
                                    :key="section.id"
                                >
                                    <TableCell>
                                        {{ section.course.course_name }}
                                    </TableCell>

                                    <TableCell>
                                        {{ section.section_name }}
                                    </TableCell>
                                </TableRow>

                                <TableRow v-if="filteredSections.length === 0">
                                    <TableCell
                                        colspan="2"
                                        class="text-center py-10"
                                    >
                                        No sections found.
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
