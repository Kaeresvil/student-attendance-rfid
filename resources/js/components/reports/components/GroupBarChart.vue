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
import { defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../../axios"

export default defineComponent({
    components: {
          apexchart: VueApexCharts,
        },

        setup(){
          const series = ref([])
          const grades = ref([])
          const chartOptions = ref({
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
              categories:  grades,
            },
          })
        

          const index = () => {
            axios.get('/api/page/attendance/groupBar')
                    .then(response => {
                      grades.value = response.data.grade
                      updateChar(grades)
                      console.log('res', chartOptions.value.xaxis.categories = response.data.grade)
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

          }

          const updateChar = (data) => {
            chartOptions.value = {
              ...chartOptions.value,
              xaxis: {
              categories: data
            }
            }
          }

          onMounted(index)

        return {
          series,
          chartOptions,
          grades
        }
      }


});
</script>
<style scoped>
</style>