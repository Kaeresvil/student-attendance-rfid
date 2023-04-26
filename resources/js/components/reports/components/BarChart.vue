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
import moment from "moment"
import { useRouter,useRoute } from 'vue-router'
export default defineComponent({
    components: {
          apexchart: VueApexCharts,
        },
        setup(){
          const series = ref([])
          const route = useRoute()
          const chartOptions = ref({
            chart: {
              id: "vuechart-example",
            },
            xaxis: {
              categories: ["On Time", "Late", "Absent",],
            },
            title: {
                  text: "Total Students Accumulated at School (Today)",
                  align: "center",
                },
          })

          const index = ( payload = {type: '', section: ''}) => {

            if(route.path.includes('reports')){
              payload = {type: 'reports', section: ''}
            }else if(route.path.includes('students-report')){
              payload = {type: 'students-reports', section: route.params.id}
            }
            axios.get('/api/page/attendance/BarChart',{params: {...payload}})
                    .then(response => {
                      console.log('sr',response.data)
                      updateChar(moment().format("A"))
                      series.value = response.data.series
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

                    

          }
          const updateChar = (data) => {
            chartOptions.value = {
              ...chartOptions.value,
              title: {
                  text: "Total Students Accumulated at School (Today) " +data,
                  align: "center",
                },
            }
          }

          onMounted(index)

        return {
          series,
          chartOptions,
        }
      }
  // data: function() {
  //   return {

  //       chartOptions: {
  //       chart: {
  //         id: "vuechart-example",
  //       },
  //       xaxis: {
  //         categories: ["On Time", "Late", "Absent",],
  //       },
  //       title: {
  //             text: "Total Students Accumulated at School (Today)",
  //             align: "center",
  //           },
  //     },
  //     series: [
  //       {
  //         name: "Total Students",
  //         data: [805, 1000, 0,],
  //       },
  //     ],
          


  //   };
  // },
});
</script>
<style scoped>
</style>