<script setup lang="ts">
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
            backgroundColor: "#6366f1",
        },
        {
            label: "Absent",
            data: safeWeekly.map((d) => Number(d.absent)),
            backgroundColor: "#f43f5e",
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

    <div class="min-h-screen bg-[#f4f7fb] p-6">
        <!-- HERO -->
        <div
            class="rounded-[32px] bg-gradient-to-r from-gray-500 to-gray-200 p-8 text-white mb-6"
        >
            <div class="flex justify-between items-start">
                <div>
                    <p class="uppercase text-xs tracking-[4px] opacity-80">
                        Attendance Analytics
                    </p>

                    <h1 class="text-4xl font-bold mt-3">
                        School Performance Dashboard
                    </h1>

                    <p class="mt-2 text-sm opacity-80">
                        Monitor student attendance and activity in real-time.
                    </p>
                </div>

                <div class="bg-white/20 backdrop-blur-xl rounded-2xl px-5 py-4">
                    <p class="text-xs opacity-70">Today</p>
                    <p class="font-semibold">
                        {{ new Date().toLocaleDateString() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- BENTO GRID -->
        <div class="grid grid-cols-12 gap-5">
            <!-- BIG CHART -->
            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white rounded-[28px] p-6 shadow-sm h-full">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-sm text-gray-500">Weekly Overview</p>

                            <h2 class="text-2xl font-bold text-gray-800">
                                Attendance Trend
                            </h2>
                        </div>

                        <div
                            class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-sm font-semibold"
                        >
                            Live Data
                        </div>
                    </div>

                    <div class="h-[350px]">
                        <AttendanceChart :data="chartData" />
                    </div>
                </div>
            </div>

            <!-- MINI STAT STACK -->
            <div class="col-span-12 lg:col-span-4 flex flex-col gap-5">
                <div
                    class="bg-white rounded-[28px] p-5 shadow-sm flex justify-between items-center"
                >
                    <div>
                        <p class="text-sm text-gray-500">Students</p>
                        <h2 class="text-3xl font-bold text-gray-800">
                            {{ props.stats.students }}
                        </h2>
                    </div>

                    <div
                        class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold"
                    >
                        ST
                    </div>
                </div>

                <div
                    class="bg-white rounded-[28px] p-5 shadow-sm flex justify-between items-center"
                >
                    <div>
                        <p class="text-sm text-gray-500">Teachers</p>
                        <h2 class="text-3xl font-bold text-gray-800">
                            {{ props.stats.teachers }}
                        </h2>
                    </div>

                    <div
                        class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center text-pink-600 font-bold"
                    >
                        TC
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-500 to-green-400 rounded-[28px] p-5 text-white"
                >
                    <p class="text-sm opacity-80">Present Today</p>
                    <h2 class="text-4xl font-bold mt-2">
                        {{ props.stats.present }}
                    </h2>

                    <div class="mt-6 flex justify-between text-sm">
                        <span>Attendance Rate</span>
                        <span>98%</span>
                    </div>
                </div>
            </div>

            <!-- PIE -->
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white rounded-[28px] p-6 shadow-sm h-full">
                    <p class="text-sm text-gray-500 mb-1">Attendance Split</p>

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        Distribution
                    </h2>

                    <div class="h-[220px]">
                        <PieChart :data="pieData" />
                    </div>

                    <div class="grid grid-cols-3 gap-3 mt-4">
                        <div class="bg-green-50 rounded-2xl p-3 text-center">
                            <p class="text-xs text-green-600">Present</p>
                            <p class="font-bold text-green-700">
                                {{ props.stats.present }}
                            </p>
                        </div>

                        <div class="bg-red-50 rounded-2xl p-3 text-center">
                            <p class="text-xs text-red-600">Absent</p>
                            <p class="font-bold text-red-700">
                                {{ props.stats.absent }}
                            </p>
                        </div>

                        <div class="bg-yellow-50 rounded-2xl p-3 text-center">
                            <p class="text-xs text-yellow-600">Late</p>
                            <p class="font-bold text-yellow-700">
                                {{ props.stats.late }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WIDE INFO -->
            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white rounded-[28px] p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-5">
                        <div>
                            <p class="text-sm text-gray-500">
                                Recent Monitoring
                            </p>

                            <h2 class="text-2xl font-bold text-gray-800">
                                Activity Feed
                            </h2>
                        </div>

                        <div class="text-sm text-gray-400">
                            {{ safeRecent.length }} recent logs
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="r in safeRecent"
                            :key="r.id"
                            class="flex items-center justify-between border border-gray-100 rounded-2xl p-4 hover:bg-gray-50 transition"
                        >
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ r.student?.fullname }}
                                </p>

                                <p class="text-sm text-gray-400">
                                    {{
                                        r.class_assignment?.course?.course_name
                                    }}
                                </p>
                            </div>

                            <div>
                                <span
                                    class="px-4 py-2 rounded-full text-xs font-semibold"
                                    :class="{
                                        'bg-green-100 text-green-700':
                                            r.status === 'present',

                                        'bg-red-100 text-red-700':
                                            r.status === 'absent',

                                        'bg-yellow-100 text-yellow-700':
                                            r.status === 'late',
                                    }"
                                >
                                    {{ r.status }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-400">
                                {{ r.date }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
