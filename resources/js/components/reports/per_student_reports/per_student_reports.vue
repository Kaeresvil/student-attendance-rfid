<template>
    <div class="div_conrainer" style="margin:0 2% 0 2%;">
           <div class="row g-2 justify-content-center">
               <!-- <div class="col-6 mr-5" style="background-color: white; width: fit-content; border-radius: 8px 8px; border: 1px solid #e1e1e1">
                 
                  <GroupBarChart></GroupBarChart>
               </div> -->
                   <div class="col-6 mr-5" style="background-color: white; width: fit-content; border-radius: 8px 8px;border: 1px solid #e1e1e1">
              
                    <PieChart></PieChart>
                 </div>
                   <div class="col-6" style="background-color: white; width: fit-content; border-radius: 8px 8px;border: 1px solid #e1e1e1">
              
                    <BarChart></BarChart>
                 </div>


        <div class="conrainer" style="margin:20px 4% 10px 4%;">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 15px; color: #312d2dd9;"> {{ this.$route.query.event }} Reports ({{ this.$route.query.date_format }}) </h4>
                    <div>
                        <button class="block uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="this.$router.go(-1)">Back</button>
                    </div>
                </div>
                <a-divider />

                    <a-row style="margin-bottom: 15px">
                    <a-col class="search">
                    <a-input-search
                        placeholder="search"
                        style="width: 300px"
                        v-model:value="form.search"
                        @input="
                        debounce(() => {
                            from.search = $event.target.value;
                        })
                        "
                    />
                    </a-col>
                    </a-row>


                    <a-table

                        size="small"
                        row-key="id"
                        :columns="columns"
                        :data-source="students.data"
                        :pagination="false"
                        :loading="loading"
                        style="overflow-x: auto"
                    >

                    <template v-slot:action="{ record }">
                        <a-button
                        class="buttonshow"
                        @click="editRecord(record)"
                        size="small"
                        >
                    View
                        </a-button>
                    </template>
                    <template v-slot:grade="{ record }">
                        <span>{{ record.grade_level.grade_level }}</span>
                    </template>
                    <template v-slot:stud_name="{ record }">
                        <span>{{ record.student.name }}</span>
                    </template>
                    <template v-slot:event="{ record }">
                        <span>{{ record.event.event_name }}</span>
                    </template>
                    <template v-slot:remarks="{ record }">
                        <a-tag :color="record.am_time_in != null && record.am_time_out != null && record.pm_time_in != null && record.pm_time_out != null  ?  '#0073b7':'#dd4b39'">
                            <span>{{ record.am_time_in != null && record.am_time_out != null && record.pm_time_in != null && record.pm_time_out != null ? 'Whole Day':'Incomplete'}}</span>
                        </a-tag>
                      
                    </template>
                    <template v-slot:date="{ record }">
                        <span>{{ dateFormat(record.created_at) }}</span>
                    </template>
                    <template v-slot:am_in="{ record }">
                        <span>{{ timeFormat(record.am_time_in) }}</span>
                    </template>
                    <template v-slot:am_out="{ record }">
                        <span>{{ timeFormat(record.am_time_out) }}</span>
                    </template>
                    <template v-slot:pm_in="{ record }">
                        <span>{{ timeFormat(record.pm_time_in) }}</span>
                    </template>
                    <template v-slot:pm_out="{ record }">
                        <span>{{ timeFormat(record.pm_time_out) }}</span>
                    </template>

                    </a-table>





                    
                <a-row style="margin: 15px 15px" v-if="true">
                    <a-col class="search" span="24">
                    <div>
                        <span
                        >{{ students.from }} - {{ students.to }} of
                        {{ students.total }}</span
                        >
                    </div>
                    <div>
                        <a-pagination
                        size="small"
                        :total="students.total"
                        show-size-changer
                        @change="onChange"
                        @showSizeChange="onShowLimit"
                        :current="form.page"
                        :default-page-size="15"
                        :page-size-options="['15', '30', '45', '60']"
                        />
                    </div>
                    </a-col>
                </a-row>

            
            
                </div>
            </div>
        </div>

       </div>
   </div>
</template>
<script>
import { defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import GroupBarChart from "../components/GroupBarChart.vue"
import PieChart from "../components/PieChart.vue"
import BarChart from "../components/BarChart.vue"
import { useRouter, useRoute } from 'vue-router'
import moment from 'moment';

import axios from "../../../axios"
export default defineComponent({
    components: {
    GroupBarChart,
    PieChart,
    BarChart,
  },

    setup(){
        const router = useRouter()
        const route = useRoute()
        const students = ref([])
        const loading = ref(true)
        const form = reactive({
        page: 1,
        id: route.params.id,
        event_id: route.query.event_id,
        forall: route.query.forALL,
        limit: 15,
        search: "",
        });

        const columns = [
        {
            title: 'LRN',
            dataIndex: 'stud_lrn',
            align: 'center'
        },
        {
            title: 'Name',
            dataIndex: 'name',
            slots: { customRender: 'stud_name' },
            align: 'center'
        },
        {
            title: 'Event Report',
            dataIndex: 'name',
            slots: { customRender: 'event' },
            align: 'center'
        },
        {
            title: 'Attendance Date',
            dataIndex: 'created_at',
            slots: { customRender: 'date' },
            align: 'center'
        },
        {
            title: 'Time In (AM)',
            dataIndex: 'am_time_in',
            slots: { customRender: 'am_in' },
            align: 'center'
        },
        {
            title: 'Time Out (AM)',
            dataIndex: 'am_time_out',
            slots: { customRender: 'am_out' },
            align: 'center'
        },
        {
            title: 'Time In (PM)',
            slots: { customRender: 'pm_in' },
            align: 'center'
        },
        {
            title: 'Time Out (PM)',
            dataIndex: 'pm_time_out',
            slots: { customRender: 'pm_out' },
            align: 'center'
        },
        {
            title: 'Remarks',
            dataIndex: 'name',
            slots: { customRender: 'remarks' },
            align: 'center'
        },
        {
            title: 'Action',
            dataIndex: 'action',
            slots: { customRender: 'action' },
            align: 'center'
        },
        ];



        const index = (payload = {page: 1, id: route.params.id, event_id: route.query.event_id, forall: route.query.forALL}) => {
            loading.value = true;
            // payload = {section: route.params.id}
            axios.get('/api/page/get/student/attendance', {params: {...payload}})
                    .then(response => {
                        students.value = response.data.data;
                        loading.value = false;
                        console.log('attendance stud', response.data)
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        }

        const onChange = (payload) => {
        const { page } = toRefs(form);
        page.value = payload;
        filter(form);
        };

    const onShowLimit = (current, pageSize) => {
      const { limit } = toRefs(form);
      const { page } = toRefs(form);
      page.value = 1;
      limit.value = pageSize;
      filter(form);
    };

    function createDebounce() {
      let timeout = null;
      return function (fnc, delayMs) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const { page } = toRefs(form);
          page.value = 1;
          filter(form);
        }, delayMs || 500);
      };
    }
        const filter = (payload) => {
      index(payload)
    }
        const editRecord = (record) => {
            // router.push({path: '/edit/students/' + record.id,
            // query: {archive: 'false'}
            // })
            console.log('record',record)
    }



///datatable return function
const dateFormat = (value) =>{
    return moment(value).format('LL');
}
const timeFormat = (value) =>{

    return value != null ? moment(value).format('MM/D/YYYY, h:mm:ss a'):'N/A';
}
    onMounted(index)

    return {
        onChange,
        onShowLimit,
        debounce: createDebounce(),
        editRecord,
        dateFormat,
        timeFormat,

        students,
        form,
        loading,
        columns
    }

    }
})
</script>
<style scoped>
.search {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  align-content: space-between;
}



</style>