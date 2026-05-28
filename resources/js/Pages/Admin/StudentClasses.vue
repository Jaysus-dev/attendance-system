<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    subjects: Array,
});

function openSubject(id: number) {
    router.visit(route('student.subject.classes', id));
}
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">My Subjects</h1>

            <!-- EMPTY STATE -->
            <div
                v-if="!subjects || subjects.length === 0"
                class="text-gray-500"
            >
                No subjects assigned yet.
            </div>

            <!-- SUBJECT GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="subject in subjects"
                    :key="subject.id"
                    class="border rounded-lg p-4 shadow hover:shadow-lg transition cursor-pointer bg-white"
                    @click="openSubject(subject)"
                >
                    <div class="text-lg font-semibold text-indigo-700">
                        {{ subject.subject_code }}
                    </div>

                    <div class="text-gray-700">
                        {{ subject.subject_name }}
                    </div>

                    <div class="text-sm text-gray-500 mt-2">
                        Click to view classes →
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
