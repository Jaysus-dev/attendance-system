<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from "@/Components/ui/card";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";

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
    dashboardStyle?: number;
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
            backgroundColor: "#16a34a",
        },
        {
            label: "Absent",
            data: safeWeekly.map((d) => Number(d.absent)),
            backgroundColor: "#dc2626",
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

    <div class="p-6 min-h-screen bg-gray-50 space-y-6">
        <!-- TOP HEADER -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Admin Dashboard Russ V.
                </h1>
                <p class="text-sm text-gray-500">Realtime insights overview</p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-500">Today</p>
                <p class="text-lg font-semibold">
                    {{ new Date().toLocaleDateString() }}
                </p>
            </div>
        </div>

        <!-- TOP KPI CARDS -->
        <div class="grid grid-cols-4 gap-4">
            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Students</p>
                    <p class="text-2xl font-bold">{{ stats.students }}</p>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Present</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ stats.present }}
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Absent</p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ stats.absent }}
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Late</p>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ stats.late }}
                    </p>
                </CardContent>
            </Card>
        </div>

        <!-- MAIN LAYOUT (LIKE YOUR IMAGE) -->
        <div class="grid grid-cols-12 gap-6">
            <!-- LEFT PANEL -->
            <div class="col-span-3 space-y-4">
                <Card>
                    <CardContent class="p-5">
                        <p class="text-xs text-gray-500">Total Students</p>
                        <p class="text-2xl font-bold">{{ stats.students }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-5">
                        <p class="text-xs text-gray-500">Courses</p>
                        <p class="text-2xl font-bold">{{ stats.courses }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-5">
                        <p class="text-xs text-gray-500">Sections</p>
                        <p class="text-2xl font-bold">{{ stats.sections }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-5">
                        <p class="text-xs text-gray-500">Teachers</p>
                        <p class="text-2xl font-bold">{{ stats.teachers }}</p>
                    </CardContent>
                </Card>
            </div>

            <!-- CENTER CHART AREA -->
            <div class="col-span-6 space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Weekly Attendance</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[320px]">
                        <AttendanceChart :data="chartData" />
                    </CardContent>
                </Card>
            </div>

            <!-- RIGHT PANEL -->
            <div class="col-span-3 space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Attendance Split</CardTitle>
                    </CardHeader>
                    <CardContent class="h-full">
                        <PieChart :data="pieData" />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
