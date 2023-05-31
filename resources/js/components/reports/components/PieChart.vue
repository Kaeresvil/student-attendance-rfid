<template>
    <div>
      <apexchart width="350" type="pie" :options="chartOptions" :series="series"></apexchart>
    </div>
  </template>
<script>
import VueApexCharts from "vue3-apexcharts";
import { defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../../axios"

import { useRouter,useRoute } from 'vue-router'

export default defineComponent({
    components: {
          apexchart: VueApexCharts,
        },

        setup(){
          const router = useRouter()
          const route = useRoute()
          const series = ref([])
          const chartOptions = ref({
            chart: {
              width: "60%",
              height: 500,
              type: 'pie',
            },
            labels: ["On Time AM", "Late AM", "On Time PM", "Late PM"],
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
              text: "All Student Report (Class Attendance)",
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
        

          const index = ( payload = {type: '', section: ''}) => {
          
            if(route.path.includes('reports')){
              payload = {type: 'reports', section: ''}
            }else if(route.path.includes('students-report')){
              payload = {type: 'students-reports', section: route.params.id}
            }
           console.log('[ath]', route.path.includes('reports'))
            axios.get('/api/page/attendance/PieChart',{params: {...payload}})
                    .then(response => {
                      series.value = response.data.series
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