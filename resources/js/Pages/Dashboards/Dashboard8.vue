<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from "@/Components/ui/card";
import { Head } from "@inertiajs/vue3";

import AttendanceChart from "@/Components/ui/chart/AttendanceChart.vue";
import PieChart from "@/Components/ui/chart/PieChart.vue";

const props = defineProps<{
    stats: any;
    weeklyAttendance: any[];
    recent: any[];
}>();

const safeWeekly = props.weeklyAttendance ?? [];
const safeRecent = props.recent ?? [];

const chartData = {
    labels: safeWeekly.map((d) => d.date),
    datasets: [
        {
            label: "Present",
            data: safeWeekly.map((d) => Number(d.present)),
            backgroundColor: "#22c55e",
        },
        {
            label: "Absent",
            data: safeWeekly.map((d) => Number(d.absent)),
            backgroundColor: "#ef4444",
        },
        {
            label: "Late",
            data: safeWeekly.map((d) => Number(d.late)),
            backgroundColor: "#f59e0b",
        },
    ],
};

const pieData = {
    present: props.stats?.present ?? 0,
    absent: props.stats?.absent ?? 0,
    late: props.stats?.late ?? 0,
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="min-h-screen bg-[#f6f7fb] text-gray-900 p-6 space-y-6">
        <!-- ================= HEADER ================= -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-800">
                    ATTENDANCE CONTROL CENTER - Sandy Paran
                </h1>
                <p class="text-sm text-gray-500">Real-time system</p>
            </div>

            <div class="bg-white border rounded-xl px-4 py-2 shadow-sm">
                <p class="text-xs text-gray-500">TODAY</p>
                <p class="font-semibold">
                    {{ new Date().toLocaleDateString() }}
                </p>
            </div>
        </div>

        <!-- ================= KPI STRIP (HORIZONTAL) ================= -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <p class="text-xs text-gray-500">Students</p>
                <p class="text-2xl font-bold">{{ props.stats.students }}</p>
            </div>

            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <p class="text-xs text-gray-500">Teachers</p>
                <p class="text-2xl font-bold">{{ props.stats.teachers }}</p>
            </div>

            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <p class="text-xs text-gray-500">Courses</p>
                <p class="text-2xl font-bold">{{ props.stats.courses }}</p>
            </div>

            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <p class="text-xs text-gray-500">Sections</p>
                <p class="text-2xl font-bold">{{ props.stats.sections }}</p>
            </div>
        </div>

        <!-- ================= MAIN VISUAL FOCUS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- BIG CENTER CHART -->
            <div
                class="lg:col-span-2 bg-white border rounded-2xl p-5 shadow-sm"
            >
                <div class="flex justify-between items-center mb-3">
                    <p class="text-sm font-semibold text-gray-600">
                        WEEKLY ATTENDANCE ANALYTICS
                    </p>

                    <div class="flex gap-2 text-xs">
                        <span class="text-green-600">● Present</span>
                        <span class="text-red-500">● Absent</span>
                        <span class="text-yellow-500">● Late</span>
                    </div>
                </div>

                <div class="h-[380px]">
                    <AttendanceChart :data="chartData" />
                </div>
            </div>

            <!-- RIGHT PANEL (STACKED INSIGHTS) -->
            <div class="space-y-4">
                <!-- STATUS MINI CARDS -->
                <div
                    class="bg-white border rounded-2xl p-4 shadow-sm space-y-3"
                >
                    <p class="text-sm font-semibold text-gray-700">
                        LIVE STATUS
                    </p>

                    <div
                        class="flex justify-between p-3 bg-green-50 rounded-xl"
                    >
                        <span class="text-green-600 text-sm">Present</span>
                        <span class="font-bold text-green-700">
                            {{ props.stats.present }}
                        </span>
                    </div>

                    <div class="flex justify-between p-3 bg-red-50 rounded-xl">
                        <span class="text-red-600 text-sm">Absent</span>
                        <span class="font-bold text-red-700">
                            {{ props.stats.absent }}
                        </span>
                    </div>

                    <div
                        class="flex justify-between p-3 bg-yellow-50 rounded-xl"
                    >
                        <span class="text-yellow-600 text-sm">Late</span>
                        <span class="font-bold text-yellow-700">
                            {{ props.stats.late }}
                        </span>
                    </div>
                </div>

                <!-- PIE CHART CARD -->
                <div class="bg-white border rounded-2xl p-4 shadow-sm">
                    <p class="text-sm font-semibold text-gray-600 mb-2">
                        DISTRIBUTION
                    </p>

                    <div class="h-[160px]">
                        <PieChart :data="pieData" />
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= RECENT ACTIVITY (MODERN LIST STYLE) ================= -->
        <div class="bg-white border rounded-2xl shadow-sm p-5">
            <div class="flex justify-between items-center mb-3">
                <p class="text-sm font-semibold text-gray-600">
                    RECENT ATTENDANCE LOGS
                </p>
            </div>

            <div class="space-y-2">
                <div
                    v-for="r in safeRecent"
                    :key="r.id"
                    class="flex justify-between items-center p-3 rounded-xl hover:bg-gray-50 border"
                >
                    <div>
                        <p class="font-medium text-sm">
                            {{ r.student?.fullname }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ r.class_assignment?.course?.course_name }}
                        </p>
                    </div>

                    <div class="text-sm">
                        <span
                            :class="{
                                'text-green-600': r.status === 'present',
                                'text-red-600': r.status === 'absent',
                                'text-yellow-600': r.status === 'late',
                            }"
                            class="font-semibold"
                        >
                            {{ r.status }}
                        </span>
                    </div>

                    <div class="text-xs text-gray-400">
                        {{ r.date }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
