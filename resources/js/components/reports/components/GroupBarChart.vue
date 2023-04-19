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

        series: [{
          name: "On Time",
            data: [44, 55, 41, 64]
          }, {
            name: "Late",
            data: [53, 32, 33, 52]
          },{
            name: "Absent",
            data: [51, 31, 31, 52]
          },
        ],
          chartOptions: {
            chart: {
              type: 'bar',
              height: 430
            },
            plotOptions: {
              bar: {
                horizontal: true,
                dataLabels: {
                  position: 'top',
                },
              }
            },
            dataLabels: {
              enabled: true,
              offsetX: -6,
              style: {
                fontSize: '12px',
                colors: ['black']
              }
            },
            stroke: {
              show: true,
              width: 1,
              colors: ['#fff']
            },
            title: {
              text: "Student Class Attendance Report",
              align: "center"
            },
            tooltip: {
              shared: true,
              intersect: false
            },
            xaxis: {
              categories: ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'],
            },
          },
          


    };
  },
};
</script>
<style scoped>
</style>