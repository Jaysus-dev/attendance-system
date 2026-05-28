<script setup lang="ts">
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
);

const props = defineProps<{
    data: {
        labels: string[];
        datasets: any[];
    };
}>();

const options = {
    responsive: true,
    maintainAspectRatio: false,
};
</script>

<template>
    <div class="h-[350px] w-full">
        <Bar :data="props.data" :options="options" />
    </div>
</template>
