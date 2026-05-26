<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

/*
|--------------------------------------------------------------------------
| ShadCN UI Components (kept except Select removed)
|--------------------------------------------------------------------------
*/
import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    teachers: any[];
    courses: any[];
    sections: any[];
    subjects: any[];
    assignments: any[];
}>();

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
const form = useForm({
    teacher_id: "",
    course_id: "",
    section_id: "",
    subject_id: "",
});

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
const submit = () => {
    form.post(route("assignments.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6 space-y-6">
            <!-- HEADER -->
            <Card>
                <CardHeader>
                    <CardTitle>Class Assignments</CardTitle>
                </CardHeader>

                <CardContent class="space-y-4">
                    <!-- FORM -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- TEACHER -->
                        <div class="space-y-1">
                            <label class="text-sm font-medium">Teacher</label>
                            <select
                                v-model="form.teacher_id"
                                class="w-full border rounded-md px-3 py-2"
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
                        </div>

                        <!-- COURSE -->
                        <div class="space-y-1">
                            <label class="text-sm font-medium">Course</label>
                            <select
                                v-model="form.course_id"
                                class="w-full border rounded-md px-3 py-2"
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
                        </div>

                        <!-- SECTION -->
                        <div class="space-y-1">
                            <label class="text-sm font-medium">Section</label>
                            <select
                                v-model="form.section_id"
                                class="w-full border rounded-md px-3 py-2"
                            >
                                <option value="">Select Section</option>
                                <option
                                    v-for="s in props.sections"
                                    :key="s.id"
                                    :value="s.id"
                                >
                                    {{ s.section_name }}
                                </option>
                            </select>
                        </div>

                        <!-- SUBJECT -->
                        <div class="space-y-1">
                            <label class="text-sm font-medium">Subject</label>
                            <select
                                v-model="form.subject_id"
                                class="w-full border rounded-md px-3 py-2"
                            >
                                <option value="">Select Subject</option>
                                <option
                                    v-for="s in props.subjects"
                                    :key="s.id"
                                    :value="s.id"
                                >
                                    {{ s.subject_code }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <Button class="w-full mt-4" @click="submit">
                        Assign Class
                    </Button>
                </CardContent>
            </Card>

            <!-- TABLE -->
            <Card>
                <CardHeader>
                    <CardTitle>Assigned Classes</CardTitle>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Teacher</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Section</TableHead>
                                <TableHead>Subject</TableHead>
                                <TableHead class="text-right">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="a in props.assignments"
                                :key="a.id"
                            >
                                <TableCell>{{ a.teacher.fullname }}</TableCell>
                                <TableCell>{{
                                    a.course.course_code
                                }}</TableCell>
                                <TableCell>{{
                                    a.section.section_name
                                }}</TableCell>
                                <TableCell>{{
                                    a.subject.subject_code
                                }}</TableCell>

                                <TableCell class="text-right">
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        @click="
                                            $inertia.delete(
                                                route(
                                                    'assignments.destroy',
                                                    a.id,
                                                ),
                                            )
                                        "
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
