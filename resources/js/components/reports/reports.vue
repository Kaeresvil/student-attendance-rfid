<template>
    <div class="div_conrainer" style="margin:0 2% 0 2%;">
           <div class="row g-2 justify-content-center">
               <div class="col-6 mr-5" style="background-color: white; width: fit-content; border-radius: 8px 8px; border: 1px solid #e1e1e1">
                 
                  <GroupBarChart></GroupBarChart>
               </div>
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
                    <h4 class="card-title my-auto" style="font-size: 15px; font-weight: bold; color: #312d2dd9;">Assigned Grade Level and Section</h4>
                    <!-- <div>
                        <button class="block uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="this.$router.push('/create/sections')">Add Section</button>
                    </div> -->
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
                            form.search = $event.target.value;
                        })
                        "
                    />
                    </a-col>
                    </a-row>


                    <a-table

                        size="small"
                        row-key="id"
                        :columns="columns"
                        :data-source="sections.data"
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
                    <template v-slot:total_student="{ record }">
                        <span>{{ record.extra.total_students }}</span>
                    </template>

                    </a-table>





                    
                <a-row style="margin: 15px 15px" v-if="true">
                    <a-col class="search" span="24">
                    <div>
                        <span
                        >{{ sections.from }} - {{ sections.to }} of
                        {{ sections.total }}</span
                        >
                    </div>
                    <div>
                        <a-pagination
                        size="small"
                        :total="sections.total"
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
import GroupBarChart from "./components/GroupBarChart.vue"
import PieChart from "./components/PieChart.vue"
import BarChart from "./components/BarChart.vue"
import { useRouter } from 'vue-router'

import axios from "../../axios"
export default defineComponent({
    components: {
    GroupBarChart,
    PieChart,
    BarChart,
  },

    setup(){
        const router = useRouter()
        const sections = ref([])
        const loading = ref(true)
        const form = reactive({
        page: 1,
        limit: 15,
        search: "",
        });

        const columns = [
        {
            title: 'Grade',
            slots: { customRender: 'grade' },
            align: 'center'
        },
        {
            title: 'Section',
            dataIndex: 'name',
            align: 'center'
        },
        {
            title: 'Total Students',
            dataIndex: 'email',
            slots: { customRender: 'total_student' },
            align: 'center'
        },
        {
            title: 'Action',
            dataIndex: 'action',
            slots: { customRender: 'action' },
            align: 'center'
        },
        ];



        const index = (payload = {page: 1}) => {
            loading.value = true;
                   axios.get('/api/page/section_reports', {params: {...payload}})
                    .then(response => {
                        sections.value = response.data.data;
                        loading.value = false;
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
            router.push({path: '/students-report/' + record.id,
            query: {grade: record.grade_level.grade_level, section: record.name}
        })
            console.log('record',record)
    }
    onMounted(index)

    return {
        onChange,
        onShowLimit,
        debounce: createDebounce(),
        editRecord,

        sections,
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