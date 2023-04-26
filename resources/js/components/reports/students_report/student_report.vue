<template>
    <div class="div_conrainer" style="margin:0 2% 0 2%;">
           <div class="row g-2 justify-content-center">
               <!-- <div class="col-6 mr-5" style="background-color: white; width: fit-content; border-radius: 8px 8px; border: 1px solid #e1e1e1">
                 
                  <GroupBarChart></GroupBarChart>
               </div> -->
                   <div class="col-6 mr-5" style="background-color: white; width: fit-content; border-radius: 8px 8px;border: 1px solid #e1e1e1">
              
                    <PieChart></PieChart>
                    <h2 style="text-align: center; font-size: large">Month of {{ monthNow }}</h2>
                    <h2 style="text-align: center; font-size: large">{{ this.$route.query.grade }} - {{ this.$route.query.section}}</h2>
                 </div>
                   <div class="col-6" style="background-color: white; width: fit-content; border-radius: 8px 8px;border: 1px solid #e1e1e1">
              
                    <BarChart></BarChart>
                 </div>


        <div class="conrainer" style="margin:20px 4% 10px 4%;">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 15px; font-weight: bold; color: #312d2dd9;"> Student Report of {{ this.$route.query.grade }} - {{ this.$route.query.section}} </h4>

                    <div>
                        <button class="block uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="this.$router.go(-1)">Back</button>
                    </div>
                </div>
                <a-divider />

                <div>
                    <div style="margin-bottom: 16px">
                    <a-button  class="btn btn-sm buttonshow" @click="reportsHandling">
                        Reports
                    </a-button>
                    </div>
                </div>

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
                    Reports
                        </a-button>
                    </template>
                    <template v-slot:grade="{ record }">
                        <span>{{ record.grade_level.grade_level }}</span>
                    </template>
                    <!-- <template v-slot:total_student="{ record }">
                        <span>{{ record.extra.total_students }}</span>
                    </template> -->

                    <template v-slot:remarks="{ record }">
                        <a-tag :color="record.extra.isPresent ?  '#4BB543':'#dd4b39'">
                            <span>{{ record.extra.isPresent ? 'Present':'Absent'}}</span>
                        </a-tag>
                      
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

                <a-modal v-model:visible="visible" title="Generate report for:" @ok="handleOk" :width=700>
                    <a-space direction="horizontal" :size="12">
                 <a-form-item
                 label="Event's"
                 >
                    <a-select
                    v-model:value="formData.event_id"
                    show-search
                    placeholder="Events"
                    style="width: 200px"
                >
                    <a-select-option
                    v-for="event in events_option"
                    :key="event.id"
                    @click="handleEvent(event.event_name)"
                   
                >
                {{ event.event_name }}
                </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item
                 label="Date Format"
                 >
                    <a-select
                    v-model:value="formData.date_format"
                    show-search
                    placeholder="Date Format"
                    style="width: 200px"
                    :options="options"
                    @change="handleChange"
                ></a-select>
            </a-form-item>

            <a-form-item
                 label="Date"
                 >
                    <a-date-picker v-if="formData.date_format === 'daily'" v-model:value="formData.date" />
                    <a-date-picker  v-if="formData.date_format === 'weekly'" v-model:value="formData.date" picker="week" />
                    <a-date-picker  v-if="formData.date_format === 'monthly'" v-model:value="formData.date" picker="month" />
                    <!-- <a-date-picker v-model:value="value4" picker="quarter" />
                    <a-date-picker v-model:value="value5" picker="year" /> -->
                </a-form-item>

                </a-space>
                </a-modal>


                <!-- ///all report -->
                <a-modal v-model:visible="visible2" :title="'Generate reports for '  + this.$route.query.grade +' - '+ this.$route.query.section" @ok="handleOk" :width=700>
                    <a-space direction="horizontal" :size="12">
                 <a-form-item
                 label="Event's"
                 >
                    <a-select
                    v-model:value="formData.event_id"
                    show-search
                    placeholder="Events"
                    style="width: 200px"
                >
                    <a-select-option
                    v-for="event in events_option"
                    :key="event.id"
                    @click="handleEvent(event.event_name)"
                   
                >
                {{ event.event_name }}
                </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item
                 label="Date Format"
                 >
                    <a-select
                    v-model:value="formData.date_format"
                    show-search
                    placeholder="Date Format"
                    style="width: 200px"
                    :options="options"
                    @change="handleChange"
                ></a-select>
            </a-form-item>

            <a-form-item
                 label="Date"
                 >
                    <a-date-picker v-if="formData.date_format === 'daily'" v-model:value="formData.date" />
                    <a-week-picker  v-if="formData.date_format === 'weekly'" v-model:value="formData.date"  />
                    <a-date-picker  v-if="formData.date_format === 'monthly'" v-model:value="formData.date" picker="month" />
                    <!-- <a-date-picker v-model:value="value4" picker="quarter" />
                    <a-date-picker v-model:value="value5" picker="year" /> -->
                </a-form-item>

                </a-space>
                </a-modal>

            
            
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
import { Modal } from 'ant-design-vue';

import axios from "../../../axios"
export default defineComponent({
    components: {
    GroupBarChart,
    PieChart,
    BarChart,
  },

    setup(){
        const router = useRouter()
        const monthNow = moment().format('MMMM');
        const route = useRoute()
        const students = ref([])
        const events_option = ref([])
        const loading = ref(true)
        const visible = ref(false);
        const visible2 = ref(false);
        const form = reactive({
        page: 1,
        section: route.params.id,
        limit: 15,
        search: "",
        });
        const formData = reactive({
        event_id: "",
        date_format: "",
        date: "",
        });


        const columns = [
        {
            title: 'Learner Reference Number',
            dataIndex: 'lrn',
            align: 'center'
        },
        {
            title: 'Name',
            dataIndex: 'name',
            align: 'center'
        },
        {
            title: 'Attendance Today',
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

        const options= ref([
        {
        value: 'monthly',
        label: 'Monthly',
        },
        {
        value: 'weekly',
        label: 'Weekly',
        },
        {
        value: 'daily',
        label: 'Daily',
        },
    ]);



        const index = (payload = {page: 1, section: route.params.id}) => {
            loading.value = true;
            // payload = {section: route.params.id}
            axios.get('/api/page/student', {params: {...payload}})
                    .then(response => {
                        students.value = response.data.data;
                        loading.value = false;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

                    axios.get('/api/page/all_events')
            .then(response => {
                events_option.value = response.data
                console.log('res',response.data)
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
    const stud_id = ref()
    const section_id = ref()
    const event_name = ref()

        const editRecord = (record) => {
            stud_id.value = record.lrn
            visible.value = true;
    }
        const reportsHandling = () => {
            visible2.value = true;
    }
    const validateField = () => {
        if (formData.event_id === "" || formData.date_format === "" || formData.date === ""){

        Modal.error({
            title: () => 'Error!',
            content: () => 'Please complete all fields ',
        });
            return false
        }
        return true
           
    }



    const handleOk = e => {
        if (validateField()){
        let dateFormat = ref()
            if(formData.date_format === "weekly"){
                dateFormat.value = 'week'
            }else if(formData.date_format === "monthly"){
                dateFormat.value = 'month'
            }
        const dateFrom = ref();
        const dateTo = ref();
        
        if(formData.date_format != 'daily'){
        dateFrom.value =  moment(formData.date.$d).startOf(dateFormat.value).format("YYYY-MM-DD")
        dateTo.value =  moment(formData.date.$d).endOf( dateFormat.value ).format("YYYY-MM-DD")
        }else{
        dateFrom.value =  moment(formData.date.$d).format("YYYY-MM-DD")
        dateTo.value =  moment(formData.date.$d).format("YYYY-MM-DD")
        }

      visible.value = false;
      let id =  visible2.value ? route.params.id:stud_id.value
      router.push({path: '/per-student-report/' + id,
      query: {event: event_name.value, 
        event_id:formData.event_id, 
        date_format: formData.date_format,
        dateFrom: dateFrom.value,
        dateTo: dateTo.value,
        forALL:visible2.value}
            })
        visible2.value = false;
        // formData.value.event_id = ""
        // formData.value.date_format = ""
        // formData.value.date = ""
        // console.log('dateFrom', dateFrom.value)
        // console.log('dateTo', dateTo.value)
        }

    };
    const handleEvent = e => {
        event_name.value = e
    };
    const handleChange = e => {
      console.log('option date format', e)
    };
    onMounted(index)

    return {
        onChange,
        onShowLimit,
        debounce: createDebounce(),
        editRecord,
        handleChange,
        handleEvent,
        reportsHandling,

        students,
        form,
        loading,
        columns,
        monthNow,

        visible,
        visible2,
        handleOk,
        formData,
        events_option,
        options,

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