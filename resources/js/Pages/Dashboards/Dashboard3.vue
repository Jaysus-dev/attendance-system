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

    <div class="min-h-screen bg-gray-50 flex">
        <!-- ================= LEFT SIDEBAR ================= -->
        <aside class="w-64 bg-white border-r p-5 space-y-6">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-xs text-gray-500">Attendance System</p>
            </div>

            <!-- BASIC COUNTS -->
            <div class="space-y-3">
                <div class="p-3 rounded-lg bg-green-50">
                    <p class="text-xs text-gray-500">Students</p>
                    <p class="text-lg font-bold">{{ props.stats.students }}</p>
                </div>

                <div class="p-3 rounded-lg bg-blue-50">
                    <p class="text-xs text-gray-500">Courses</p>
                    <p class="text-lg font-bold">{{ props.stats.courses }}</p>
                </div>

                <div class="p-3 rounded-lg bg-purple-50">
                    <p class="text-xs text-gray-500">Sections</p>
                    <p class="text-lg font-bold">{{ props.stats.sections }}</p>
                </div>

                <div class="p-3 rounded-lg bg-yellow-50">
                    <p class="text-xs text-gray-500">Teachers</p>
                    <p class="text-lg font-bold">{{ props.stats.teachers }}</p>
                </div>
            </div>

            <!-- ATTENDANCE SUMMARY (MOVED HERE) -->
            <div class="pt-4 border-t space-y-3">
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Today Attendance
                </p>

                <div class="p-3 rounded-lg bg-green-50">
                    <p class="text-xs text-gray-500">Present</p>
                    <p class="text-lg font-bold text-green-600">
                        {{ props.stats.present }}
                    </p>
                </div>

                <div class="p-3 rounded-lg bg-red-50">
                    <p class="text-xs text-gray-500">Absent</p>
                    <p class="text-lg font-bold text-red-600">
                        {{ props.stats.absent }}
                    </p>
                </div>

                <div class="p-3 rounded-lg bg-yellow-50">
                    <p class="text-xs text-gray-500">Late</p>
                    <p class="text-lg font-bold text-yellow-600">
                        {{ props.stats.late }}
                    </p>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT ================= -->
        <main class="flex-1 p-6 space-y-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Dashboard Overview - Rai Sanchez
                    </h2>
                    <p class="text-sm text-gray-500">
                        Real-time attendance analytics
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-xs text-gray-500">Today</p>
                    <p class="font-semibold">
                        {{ new Date().toLocaleDateString() }}
                    </p>
                </div>
            </div>

            <!-- ================= CHARTS ================= -->
            <div class="grid grid-cols-12 gap-6">
                <!-- LINE/STACKED CHART -->
                <div class="col-span-8">
                    <Card>
                        <CardHeader>
                            <CardTitle>Weekly Attendance Trend</CardTitle>
                        </CardHeader>
                        <CardContent class="h-[340px]">
                            <AttendanceChart :data="chartData" />
                        </CardContent>
                    </Card>
                </div>

                <!-- PIE + QUICK STATS -->
                <div class="col-span-4 space-y-6 h-full">
                    <Card>
                        <CardHeader>
                            <CardTitle>Attendance Breakdown</CardTitle>
                        </CardHeader>
                        <CardContent class="h-full">
                            <PieChart :data="pieData" />
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- ================= RECENT TABLE ================= -->
            <Card>
                <CardHeader>
                    <CardTitle>Recent Attendance Logs</CardTitle>
                </CardHeader>

                <CardContent>
                    <table class="w-full text-sm">
                        <thead class="text-left border-b">
                            <tr>
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
                                <td class="p-2">
                                    {{ r.student?.fullname }}
                                </td>
                                <td class="p-2">
                                    {{
                                        r.class_assignment?.course?.course_name
                                    }}
                                </td>
                                <td class="p-2">
                                    <span
                                        :class="{
                                            'text-green-600':
                                                r.status === 'present',
                                            'text-red-600':
                                                r.status === 'absent',
                                            'text-yellow-600':
                                                r.status === 'late',
                                        }"
                                    >
                                        {{ r.status }}
                                    </span>
                                </td>
                                <td class="p-2">
                                    {{ r.date }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CardContent>
            </Card>
        </main>
    </div>
</template>
