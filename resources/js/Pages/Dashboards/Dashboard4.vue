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

    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 p-6 space-y-6"
    >
        <!-- ================= HEADER ================= -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Attendance Dashboard - Angie
                </h1>
                <p class="text-sm text-gray-500">
                    Real-time analytics overview
                </p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-500">Today</p>
                <p class="font-semibold">
                    {{ new Date().toLocaleDateString() }}
                </p>
            </div>
        </div>

        <!-- ================= KPI GRID (GLASS STYLE) ================= -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div
                class="backdrop-blur-xl bg-white/70 border rounded-2xl p-5 shadow-sm"
            >
                <p class="text-xs text-gray-500">Students</p>
                <p class="text-2xl font-bold">{{ stats.students }}</p>
            </div>

            <div
                class="backdrop-blur-xl bg-white/70 border rounded-2xl p-5 shadow-sm"
            >
                <p class="text-xs text-gray-500">Teachers</p>
                <p class="text-2xl font-bold">{{ stats.teachers }}</p>
            </div>

            <div
                class="backdrop-blur-xl bg-white/70 border rounded-2xl p-5 shadow-sm"
            >
                <p class="text-xs text-gray-500">Courses</p>
                <p class="text-2xl font-bold">{{ stats.courses }}</p>
            </div>

            <div
                class="backdrop-blur-xl bg-white/70 border rounded-2xl p-5 shadow-sm"
            >
                <p class="text-xs text-gray-500">Sections</p>
                <p class="text-2xl font-bold">{{ stats.sections }}</p>
            </div>
        </div>

        <!-- ================= ATTENDANCE STATUS BANNER ================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl p-5 bg-green-50 border">
                <p class="text-sm text-gray-600">Present Today</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ stats.present }}
                </p>
            </div>

            <div class="rounded-2xl p-5 bg-red-50 border">
                <p class="text-sm text-gray-600">Absent Today</p>
                <p class="text-3xl font-bold text-red-600">
                    {{ stats.absent }}
                </p>
            </div>

            <div class="rounded-2xl p-5 bg-yellow-50 border">
                <p class="text-sm text-gray-600">Late Today</p>
                <p class="text-3xl font-bold text-yellow-600">
                    {{ stats.late }}
                </p>
            </div>
        </div>

        <!-- ================= CHART SECTION ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- MAIN CHART -->
            <div class="lg:col-span-2">
                <Card class="rounded-2xl shadow-sm">
                    <CardHeader>
                        <CardTitle>Weekly Attendance Trend</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[350px]">
                        <AttendanceChart :data="chartData" />
                    </CardContent>
                </Card>
            </div>

            <!-- PIE + STATS -->
            <div class="space-y-4">
                <Card class="rounded-2xl shadow-sm">
                    <CardHeader>
                        <CardTitle>Distribution</CardTitle>
                    </CardHeader>
                    <CardContent class="h-full">
                        <PieChart :data="pieData" />
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- ================= RECENT LOGS ================= -->
        <Card class="rounded-2xl shadow-sm">
            <CardHeader>
                <CardTitle>Recent Attendance</CardTitle>
            </CardHeader>

            <CardContent>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-gray-500">
                            <th class="p-2">Student</th>
                            <th class="p-2">Course</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Date</th>
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
                                        'text-green-600':
                                            r.status === 'present',
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
            </CardContent>
        </Card>
    </div>
</template>
