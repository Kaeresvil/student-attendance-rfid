<template>
    <div>
      <apexchart width="350" type="pie" :options="chartOptions" :series="series"></apexchart>
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
      series: [25, 15, 44,],
          chartOptions: {
            chart: {
              width: "50%",
              height: 350,
              type: 'pie',
            },
            labels: ["Absent", "Late", "On Time"],
            theme: {
              monochrome: {
                enabled: true
              }
            },
            plotOptions: {
              pie: {
                dataLabels: {
                  offset: -5
                }
              }
            },
            title: {
              text: "Student Event's Report (Class Attendance)",
              align: "center"
            },
            dataLabels: {
              formatter(val, opts) {
                const name = opts.w.globals.labels[opts.seriesIndex]
                return [name, val.toFixed(1) + '%']
              }
            },
            legend: {
              show: false
            }
          },
          
    };
  },
};
</script>
<style scoped>
</style>