<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from "@/Components/ui/card";
import { Head, router } from "@inertiajs/vue3";
import { computed } from "vue";

import AttendanceChart from "@/Components/ui/chart/AttendanceChart.vue";
import PieChart from "@/Components/ui/chart/PieChart.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Dashboard1 from "./Dashboards/Dashboard1.vue";
import Dashboard2 from "./Dashboards/Dashboard2.vue";
import Dashboard3 from "./Dashboards/Dashboard3.vue";
import Dashboard4 from "./Dashboards/Dashboard4.vue";
import Dashboard5 from "./Dashboards/Dashboard5.vue";
import Dashboard6 from "./Dashboards/Dashboard6.vue";
import Dashboard7 from "./Dashboards/Dashboard7.vue";
import Dashboard8 from "./Dashboards/Dashboard8.vue";
import Dashboard9 from "./Dashboards/Dashboard9.vue";

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

const map: Record<number, any> = {
    1: Dashboard1,
    2: Dashboard2,
    3: Dashboard3,
    4: Dashboard4,
    5: Dashboard5,
    6: Dashboard6,
    7: Dashboard7,
    8: Dashboard8,
    9: Dashboard9,
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <!-- ✅ ONLY ONE DASHBOARD RENDERS -->
        <component
            :is="map[current]"
            :stats="stats"
            :weeklyAttendance="weeklyAttendance"
            :recent="recent"
        />
    </AuthenticatedLayout>
</template>
