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

    <div class="min-h-screen bg-[#0b1220] text-white p-6 space-y-6">
        <!-- ================= HEADER ================= -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-wide">
                    SYSTEM CONTROL DASHBOARD - Castor Elaiza
                </h1>
                <p class="text-sm text-gray-400">
                    Live attendance monitoring system
                </p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-400">DATE</p>
                <p class="font-semibold">
                    {{ new Date().toLocaleDateString() }}
                </p>
            </div>
        </div>

        <!-- ================= TOP STATUS GRID ================= -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-[#111a2e] border border-white/10 rounded-xl p-5">
                <p class="text-xs text-gray-400">Students</p>
                <p class="text-3xl font-bold text-blue-400">
                    {{ props.stats.students }}
                </p>
            </div>

            <div class="bg-[#111a2e] border border-white/10 rounded-xl p-5">
                <p class="text-xs text-gray-400">Teachers</p>
                <p class="text-3xl font-bold text-purple-400">
                    {{ props.stats.teachers }}
                </p>
            </div>

            <div class="bg-[#111a2e] border border-white/10 rounded-xl p-5">
                <p class="text-xs text-gray-400">Courses</p>
                <p class="text-3xl font-bold text-cyan-400">
                    {{ props.stats.courses }}
                </p>
            </div>

            <div class="bg-[#111a2e] border border-white/10 rounded-xl p-5">
                <p class="text-xs text-gray-400">Sections</p>
                <p class="text-3xl font-bold text-pink-400">
                    {{ props.stats.sections }}
                </p>
            </div>
        </div>

        <!-- ================= ALERT STATUS BAR ================= -->
        <div class="grid grid-cols-3 gap-4">
            <div
                class="bg-green-500/10 border border-green-500/30 rounded-xl p-4"
            >
                <p class="text-xs text-green-400">PRESENT</p>
                <p class="text-2xl font-bold">{{ props.stats.present }}</p>
            </div>

            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                <p class="text-xs text-red-400">ABSENT</p>
                <p class="text-2xl font-bold">{{ props.stats.absent }}</p>
            </div>

            <div
                class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4"
            >
                <p class="text-xs text-yellow-400">LATE</p>
                <p class="text-2xl font-bold">{{ props.stats.late }}</p>
            </div>
        </div>

        <!-- ================= ANALYTICS GRID ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- MAIN TREND -->
            <div
                class="lg:col-span-2 bg-[#111a2e] border border-white/10 rounded-2xl p-4"
            >
                <p class="text-sm text-gray-400 mb-2">WEEKLY ATTENDANCE FLOW</p>
                <div class="h-[360px]">
                    <AttendanceChart :data="chartData" />
                </div>
            </div>

            <!-- SIDE PANEL -->
            <div class="space-y-4 h-full">
                <div
                    class="bg-[#111a2e] border border-white/10 rounded-2xl p-4 h-full"
                >
                    <p class="text-sm text-gray-400 mb-2">DISTRIBUTION</p>
                    <div class="h-full">
                        <PieChart :data="pieData" />
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= ACTIVITY LOG ================= -->
        <div class="bg-[#111a2e] border border-white/10 rounded-2xl p-4">
            <p class="text-sm text-gray-400 mb-3">RECENT ACTIVITY LOG</p>

            <table class="w-full text-sm">
                <thead class="text-gray-400 border-b border-white/10">
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
                        class="border-b border-white/5 hover:bg-white/5"
                    >
                        <td class="p-2">{{ r.student?.fullname }}</td>
                        <td class="p-2">
                            {{ r.class_assignment?.course?.course_name }}
                        </td>
                        <td class="p-2">
                            <span
                                :class="{
                                    'text-green-400': r.status === 'present',
                                    'text-red-400': r.status === 'absent',
                                    'text-yellow-400': r.status === 'late',
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
