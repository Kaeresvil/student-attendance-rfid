<template>
    <div>
    <apexchart
      width="500"
      type="bar"
      :options="chartOptions"
      :series="series"
    ></apexchart>
    </div>
  </template>
<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    components: {
          apexchart: VueApexCharts,
        },
  data: function() {
    return {

        chartOptions: {
        chart: {
          id: "vuechart-example",
        },
        xaxis: {
          categories: ["On Time Students", "Late Comers", "Absentees",],
        },
        title: {
              text: "Total Students Accumulated at School (Today)",
              align: "center",
            },
      },
      series: [
        {
          name: "Total Students",
          data: [805, 1000, 0,],
        },
        {
          name: "Percentage",
          data: [80, 100, 0,],
        },
      ],
          


    };
  },
};
</script>
<style scoped>
</style>