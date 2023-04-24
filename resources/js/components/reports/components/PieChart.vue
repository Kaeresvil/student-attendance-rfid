<template>
    <div>
      <apexchart width="350" type="pie" :options="chartOptions" :series="series"></apexchart>
    </div>
  </template>
<script>
import VueApexCharts from "vue3-apexcharts";
import { defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../../axios"

export default defineComponent({
    components: {
          apexchart: VueApexCharts,
        },

        setup(){
          const series = ref([])
          const chartOptions = ref({
            chart: {
              width: "60%",
              height: 500,
              type: 'pie',
            },
            labels: ["On Time", "Late"],
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
          })
        

          const index = () => {
            axios.get('/api/page/attendance/PieChart')
                    .then(response => {
                      series.value = response.data.series
                      console.log('res',response.data)
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

          }

          // const updateChar = (data) => {
          //   chartOptions.value = {
          //     ...chartOptions.value,
          //     xaxis: {
          //     categories: data
          //   }
          //   }
          // }

          onMounted(index)

        return {
          series,
          chartOptions,
        }

      }
});
</script>
<style scoped>
</style>