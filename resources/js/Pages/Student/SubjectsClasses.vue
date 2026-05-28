<script setup>
import { router } from "@inertiajs/vue3";

defineProps({
    classes: Array,
});

function viewAttendance(classId) {
    router.visit(`/my-attendance/${classId}`);
}
</script>

<template>
    <div>
        <h1 class="text-xl font-bold mb-4">Subject Classes</h1>

        <div v-for="cls in classes" :key="cls.id" class="p-3 border mb-2">
            <p>Teacher: {{ cls.teacher.fullname }}</p>
            <p>Section: {{ cls.section.name }}</p>

            <button @click="viewAttendance(cls.id)">View Attendance</button>
        </div>
    </div>
</template>
