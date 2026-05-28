<script setup lang="ts">
import { Pie } from "vue-chartjs";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps<{
    data: {
        present: number;
        absent: number;
        late: number;
    };
}>();

const chartData = {
    labels: ["Present", "Absent", "Late"],
    datasets: [
        {
            data: [props.data.present, props.data.absent, props.data.late],
            backgroundColor: ["#22c55e", "#ef4444", "#f59e0b"],
            borderWidth: 1,
        },
    ],
};

const options = {
    responsive: true,
    maintainAspectRatio: false,
};
</script>

<template>
    <div class="h-full w-full">
        <Pie :data="chartData" :options="options" />
    </div>
</template>
