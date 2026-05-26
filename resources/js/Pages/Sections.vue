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
    return props.sections.filter((section) => {
        const q = search.value.toLowerCase();

        return (
            section.section_name.toLowerCase().includes(q) ||
            section.course.course_name.toLowerCase().includes(q)
        );
    });
});

/*
|--------------------------------------------------------------------------
| Modal State
|--------------------------------------------------------------------------
*/
const open = ref(false);
const editMode = ref(false);
const selectedSectionId = ref<number | null>(null);

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
| RESET FORM
|--------------------------------------------------------------------------
*/
const resetForm = () => {
    form.clearErrors();

    form.course_id = "";
    form.section_name = "";

    editMode.value = false;
    selectedSectionId.value = null;
};

/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/
const editSection = (section: Section) => {
    editMode.value = true;
    selectedSectionId.value = section.id;
    open.value = true;

    form.course_id = String(section.course.id);
    form.section_name = section.section_name;
};

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/
const deleteSection = (id: number) => {
    if (confirm("Are you sure you want to delete this section?")) {
        form.delete(route("sections.destroy", id), {
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
    if (editMode.value && selectedSectionId.value) {
        form.put(route("sections.update", selectedSectionId.value), {
            preserveScroll: true,
            onSuccess: () => {
                resetForm();
                open.value = false;
            },
        });
    } else {
        form.post(route("sections.store"), {
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
                    <!-- SEARCH + ADD -->
                    <div class="flex justify-between items-center">
                        <!-- SEARCH -->
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
                                <Button @click="resetForm" class="gap-2">
                                    <Plus class="w-4 h-4" />
                                    Add Section
                                </Button>
                            </DialogTrigger>

                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>
                                        {{
                                            editMode
                                                ? "Edit Section"
                                                : "Add Section"
                                        }}
                                    </DialogTitle>
                                </DialogHeader>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                >
                                    <!-- COURSE -->
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

                                    <!-- SECTION NAME -->
                                    <Input
                                        v-model="form.section_name"
                                        placeholder="Section Name (e.g. BSIT-1A)"
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
                                    <TableHead>Course</TableHead>
                                    <TableHead>Section Name</TableHead>
                                    <TableHead>Actions</TableHead>
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

                                    <!-- ACTIONS -->
                                    <TableCell class="flex gap-2">
                                        <Button
                                            size="sm"
                                            class="bg-yellow-500 text-white"
                                            @click="editSection(section)"
                                        >
                                            Edit
                                        </Button>

                                        <Button
                                            size="sm"
                                            class="bg-red-600 text-white"
                                            @click="deleteSection(section.id)"
                                        >
                                            Delete
                                        </Button>
                                    </TableCell>
                                </TableRow>

                                <TableRow v-if="filteredSections.length === 0">
                                    <TableCell
                                        colspan="3"
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
