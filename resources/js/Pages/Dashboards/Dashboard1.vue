<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from "@/Components/ui/card";
import { Head, router } from "@inertiajs/vue3";
import { computed } from "vue";

import AttendanceChart from "@/Components/ui/chart/AttendanceChart.vue";
import PieChart from "@/Components/ui/chart/PieChart.vue";

/*
|--------------------------------------------------------------------------
| PROPS (REUSABLE FOR ALL 9 DASHBOARDS)
|--------------------------------------------------------------------------
*/
const props = defineProps<{
    stats: any;
    weeklyAttendance: any[];
    recent: any[];
    dashboardStyle?: number;
}>();

const current = computed(() => props.dashboardStyle ?? 1);

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

/*
|--------------------------------------------------------------------------
| PIE DATA
|--------------------------------------------------------------------------
*/
const pieData = {
    present: props.stats?.present ?? 0,
    absent: props.stats?.absent ?? 0,
    late: props.stats?.late ?? 0,
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="p-6 space-y-8 bg-pink-50 min-h-screen">
        <!-- HEADER -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Admin Dashboard - Sandy Yet
            </h1>
            <p class="text-sm text-gray-500">
                System analytics & performance overview
            </p>
        </div>

        <!-- ================= MAIN STATS ================= -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Students</p>
                    <p class="text-2xl font-bold">
                        {{ props.stats?.students ?? 0 }}
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Teachers</p>
                    <p class="text-2xl font-bold">
                        {{ props.stats?.teachers ?? 0 }}
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Courses</p>
                    <p class="text-2xl font-bold">
                        {{ props.stats?.courses ?? 0 }}
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Subjects</p>
                    <p class="text-2xl font-bold">
                        {{ props.stats?.subjects ?? 0 }}
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Sections</p>
                    <p class="text-2xl font-bold">
                        {{ props.stats?.sections ?? 0 }}
                    </p>
                </CardContent>
            </Card>
        </div>

        <!-- ================= ADMIN CONTROL ================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Card class="border-l-4 border-yellow-500">
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Pending Approval</p>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ props.stats?.pending ?? 0 }}
                    </p>
                </CardContent>
            </Card>

            <Card class="border-l-4 border-green-500">
                <CardContent class="p-5">
                    <p class="text-xs text-gray-500">Approved Accounts</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ props.stats?.approved ?? 0 }}
                    </p>
                </CardContent>
            </Card>

            <Card class="border-l-4 border-blue-500">
                <CardContent class="p-5 space-y-2">
                    <p class="text-xs text-gray-500">
                        Attendance Today Summary
                    </p>

                    <div class="text-sm font-semibold space-y-1">
                        <p class="text-green-600">
                            Present: {{ props.stats?.present ?? 0 }}
                        </p>
                        <p class="text-red-600">
                            Absent: {{ props.stats?.absent ?? 0 }}
                        </p>
                        <p class="text-yellow-600">
                            Late: {{ props.stats?.late ?? 0 }}
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- ================= CHARTS ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>Weekly Attendance</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="h-[350px]">
                        <AttendanceChart :data="chartData" />
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Distribution</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="h-[350px] flex items-center justify-center">
                        <PieChart :data="pieData" />
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- ================= RECENT ================= -->
        <Card>
            <CardHeader>
                <CardTitle>Recent Attendance</CardTitle>
            </CardHeader>

            <CardContent>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-100">
                            <th class="p-3">Student</th>
                            <th class="p-3">Course</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="r in safeRecent" :key="r.id">
                            <td class="p-3">
                                {{ r.student?.fullname }}
                            </td>
                            <td class="p-3">
                                {{ r.class_assignment?.course?.course_name }}
                            </td>
                            <td class="p-3">
                                {{ r.status }}
                            </td>
                            <td class="p-3">
                                {{ r.date }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardContent>
        </Card>
    </div>
</template>
