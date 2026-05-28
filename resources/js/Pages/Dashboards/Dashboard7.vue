<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from "@/Components/ui/card";
import { Head } from "@inertiajs/vue3";

import AttendanceChart from "@/Components/ui/chart/AttendanceChart.vue";
import PieChart from "@/Components/ui/chart/PieChart.vue";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    stats: any;
    weeklyAttendance: any[];
    recent: any[];
}>();

const safeWeekly = props.weeklyAttendance ?? [];
const safeRecent = props.recent ?? [];

/*
|--------------------------------------------------------------------------
| CHART DATA
|--------------------------------------------------------------------------
*/
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

    <div class="min-h-screen bg-gray-50 text-gray-900 p-6 space-y-6">
        <!-- ================= HEADER ================= -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    SYSTEM CONTROL DASHBOARD
                </h1>
                <p class="text-sm text-gray-500">
                    Live attendance monitoring system
                </p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-400">DATE</p>
                <p class="font-semibold text-gray-700">
                    {{ new Date().toLocaleDateString() }}
                </p>
            </div>
        </div>

        <!-- ================= MAIN LAYOUT ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- LEFT: MAIN CHART -->
            <div
                class="lg:col-span-2 bg-white border rounded-2xl p-4 shadow-sm"
            >
                <p class="text-sm text-gray-500 mb-2">WEEKLY ATTENDANCE FLOW</p>
                <div class="h-[420px]">
                    <AttendanceChart :data="chartData" />
                </div>
                <!-- ================= STATS GRID ================= -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white border rounded-xl p-5 shadow-sm">
                        <p class="text-xs text-gray-500">Students</p>
                        <p class="text-2xl font-bold">
                            {{ props.stats.students }}
                        </p>
                    </div>

                    <div class="bg-white border rounded-xl p-5 shadow-sm">
                        <p class="text-xs text-gray-500">Teachers</p>
                        <p class="text-2xl font-bold">
                            {{ props.stats.teachers }}
                        </p>
                    </div>

                    <div class="bg-white border rounded-xl p-5 shadow-sm">
                        <p class="text-xs text-gray-500">Courses</p>
                        <p class="text-2xl font-bold">
                            {{ props.stats.courses }}
                        </p>
                    </div>

                    <div class="bg-white border rounded-xl p-5 shadow-sm">
                        <p class="text-xs text-gray-500">Sections</p>
                        <p class="text-2xl font-bold">
                            {{ props.stats.sections }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- RIGHT: SIDE PANEL -->
            <div class="space-y-4">
                <!-- STATUS STACK -->
                <div
                    class="bg-white border rounded-2xl p-4 shadow-sm space-y-3"
                >
                    <p class="text-sm font-semibold text-gray-700 mb-2">
                        ATTENDANCE STATUS Castor Elaiza
                    </p>

                    <div
                        class="bg-green-50 border border-green-200 rounded-xl p-3"
                    >
                        <p class="text-xs text-green-600">PRESENT</p>
                        <p class="text-xl font-bold text-green-700">
                            {{ props.stats.present }}
                        </p>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-xl p-3">
                        <p class="text-xs text-red-600">ABSENT</p>
                        <p class="text-xl font-bold text-red-700">
                            {{ props.stats.absent }}
                        </p>
                    </div>

                    <div
                        class="bg-yellow-50 border border-yellow-200 rounded-xl p-3"
                    >
                        <p class="text-xs text-yellow-600">LATE</p>
                        <p class="text-xl font-bold text-yellow-700">
                            {{ props.stats.late }}
                        </p>
                    </div>
                </div>

                <!-- PIE CHART (UNDER STATUS) -->
                <div class="bg-white border rounded-2xl p-4 shadow-sm">
                    <p class="text-sm text-gray-500 mb-2">DISTRIBUTION</p>
                    <div class="h-full">
                        <PieChart :data="pieData" />
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= RECENT TABLE ================= -->
        <div class="bg-white border rounded-2xl p-4 shadow-sm">
            <p class="text-sm text-gray-500 mb-3">RECENT ACTIVITY LOG</p>

            <table class="w-full text-sm">
                <thead class="text-gray-500 border-b">
                    <tr>
                        <th class="p-2 text-left">Student</th>
                        <th class="p-2 text-left">Course</th>
                        <th class="p-2 text-left">Status</th>
                        <th class="p-2 text-left">Date</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="r in safeRecent"
                        :key="r.id"
                        class="border-b hover:bg-gray-50"
                    >
                        <td class="p-2">{{ r.student?.fullname }}</td>
                        <td class="p-2">
                            {{ r.class_assignment?.course?.course_name }}
                        </td>
                        <td class="p-2">
                            <span
                                :class="{
                                    'text-green-600': r.status === 'present',
                                    'text-red-600': r.status === 'absent',
                                    'text-yellow-600': r.status === 'late',
                                }"
                            >
                                {{ r.status }}
                            </span>
                        </td>
                        <td class="p-2">{{ r.date }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
