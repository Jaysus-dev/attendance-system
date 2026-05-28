<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { Input } from "@/Components/ui/input";

import { Search, BookOpen } from "lucide-vue-next";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    classes: Array<any>;
}>();

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
const search = ref("");

const filteredClasses = computed(() => {
    return props.classes.filter((cls) => {
        const q = search.value.toLowerCase();

        return (
            cls.subject?.subject_name?.toLowerCase().includes(q) ||
            cls.subject?.subject_code?.toLowerCase().includes(q) ||
            cls.teacher?.fullname?.toLowerCase().includes(q) ||
            cls.course?.course_name?.toLowerCase().includes(q) ||
            cls.section?.section_name?.toLowerCase().includes(q)
        );
    });
});
</script>

<template>
    <Head title="Subject Classes" />

    <AuthenticatedLayout>
        <div class="p-4 space-y-6">
            <!-- CARD -->
            <Card>
                <CardHeader>
                    <!-- HEADER -->
                    <div class="flex justify-between items-center">
                        <div>
                            <CardTitle> Subject Classes </CardTitle>

                            <CardDescription>
                                List of all assigned classes
                            </CardDescription>
                        </div>

                        <div
                            class="flex items-center gap-2 text-muted-foreground"
                        >
                            <BookOpen class="w-4 h-4" />

                            <span> Total: {{ filteredClasses.length }} </span>
                        </div>
                    </div>

                    <!-- SEARCH -->
                    <div class="flex gap-4 mt-4">
                        <div class="relative w-full max-w-sm">
                            <Search
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
                            />

                            <Input
                                v-model="search"
                                placeholder="Search class..."
                                class="pl-10"
                            />
                        </div>
                    </div>
                </CardHeader>

                <CardContent>
                    <!-- TABLE -->
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead> Subject </TableHead>

                                <TableHead> Teacher </TableHead>

                                <TableHead> Course </TableHead>

                                <TableHead> Section </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="cls in filteredClasses"
                                :key="cls.id"
                                class="hover:bg-muted/50"
                            >
                                <TableCell class="font-medium">
                                    {{ cls.subject?.subject_name }}
                                    <div class="text-xs text-muted-foreground">
                                        {{ cls.subject?.subject_code }}
                                    </div>
                                </TableCell>

                                <TableCell>
                                    {{ cls.teacher?.fullname }}
                                </TableCell>

                                <TableCell>
                                    {{ cls.course?.course_name }}
                                </TableCell>

                                <TableCell>
                                    {{ cls.section?.section_name }}
                                </TableCell>
                            </TableRow>

                            <!-- EMPTY -->
                            <TableRow v-if="filteredClasses.length === 0">
                                <TableCell
                                    colspan="4"
                                    class="text-center py-8 text-muted-foreground"
                                >
                                    No classes found
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
