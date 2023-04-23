<template>
    <div class="conrainer" style="margin:0 2% 0 2%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <a @click="showModal()" class="card-title my-auto" style="font-size: 15px; font-weight: bold; color: #312d2dd9;"><u>Students List</u></a>
                    <div>
                        <button class="block uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="this.$router.push('/create/students')">Add Student</button>
                    </div>
                </div>
                <a-divider />

                <div>
                    <div style="margin-bottom: 16px">
                    <a-button  class="btn btn-sm buttonshow" :disabled="!hasSelected" @click="changeBTN">
                        Change
                    </a-button>
                    <!-- <a-button  class="btn btn-sm danger ml-1 buttondelete" :disabled="!hasSelected" @click="changeBTN">
                        Delete
                    </a-button> -->
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
                        form.search = $event.target.value;
                    })
                    "
                />
                </a-col>
                </a-row>


                <a-table
                v-if="isSelected"

                    size="small"
                    row-key="id"
                    :columns="columns"
                    :data-source="users.data"
                    :pagination="false"
                    :loading="loading"
                    style="overflow-x: auto"
                    :row-selection="{ selectedRowKeys: selectedRowKeys, selectedRows: selectedRows, onChange: onSelectChange }"
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
                    <span>{{ record.section.grade_level.grade_level }}</span>
                </template>

                <template v-slot:section="{ record }">
                    <span>{{ record.section.name }}</span>
                </template>

                </a-table>





                
            <a-row style="margin: 15px 15px" v-if="true">
                <a-col class="search" span="24">
                <div>
                    <span
                    >{{ users.from }} - {{ users.to }} of
                    {{ users.total }}</span
                    >
                </div>
                <div>
                    <a-pagination
                    size="small"
                    :total="users.total"
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
</template>

<script>
import { computed, defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../axios"
import { useRouter } from 'vue-router'
import Swal from "sweetalert2";

import { ColumnProps } from 'ant-design-vue/es/table/interface';



export default defineComponent({
components:{},
setup(){
    const users = ref([])
    const selectedRowstud = ref()
    const option_grade_section = ref({});
    const selected_section = ref();
    const loading = ref(true)
    const isSelected = ref(false)
    const router = useRouter()
    const form = reactive({
      page: 1,
      limit: 15,
      search: "",
      section: "",
    });

    const columns = [
        {
            title: 'Name',
            dataIndex: 'name',
        },
        {
            title: 'Learner Reference Number (LRN)',
            dataIndex: 'lrn',
        },
        {
            title: 'Grade',
            dataIndex: 'role',
            slots: { customRender: 'grade' },
            align: 'center'
        },
        {
            title: 'Section',
            dataIndex: 'name',
            slots: { customRender: 'section' },
            align: 'center'
        },
        {
            title: 'Address',
            dataIndex: 'address',
        },
        {
            title: 'Action',
            dataIndex: 'action',
            slots: { customRender: 'action' },
            align: 'center'
        },
        ];

        const state = reactive({
      selectedRowKeys: [],
    });
    const hasSelected = computed(() => state.selectedRowKeys.length > 0);




        const changeBTN = () => {
   
            delete option_grade_section.value[1]; 
            Swal.fire({
            title: 'Select Grade Level and Section',
            input: 'select',
            inputOptions: option_grade_section.value,
            inputPlaceholder: 'Select a grade and section',
            showCancelButton: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                  
                   
                    selectedRowstud.section_id = value;
                    axios.post('/api/page/students/change',selectedRowstud)
                        .then(response => {
                            resolve()
                            state.selectedRowKeys = []; 
                         console.log(response)
                         onChange()
                                
                        })
                        .catch(function (error) {
                        console.log(error)
                        });
                })
              }
            })

            
        };

        const onSelectChange = (selectedRowKeys, selectedRows) => {
        selectedRowstud.value = selectedRows
        state.selectedRowKeys = selectedRowKeys;
        };



        const indexAction = (payload = {page: 1}) => {
            loading.value = true;
                   axios.get('/api/page/student', {params: {...payload}})
                    .then(response => {
                        users.value = response.data.data;
                        loading.value = false;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        }

        const index = (payload = {page: 1}) => {

              //get grade and section
              axios.get('/api/page/all_section')
            .then(response => {
                Object.assign(option_grade_section.value, {1: 'ALL'});
                response.data.forEach((datum) =>{
                     Object.assign(option_grade_section.value, {[datum.id]: datum.grade_level.grade_level+"-"+datum.name});
                })
               
                Swal.fire({
            title: 'Select Grade Level and Section',
            input: 'select',
            inputOptions: option_grade_section.value,
            inputPlaceholder: 'Select a grade and section',
            showCancelButton: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    selected_section.value = value
                    payload = {section: value}
                    resolve()
                    loading.value = true;
                    isSelected.value = true
                   axios.get('/api/page/student', {params: {...payload}})
                    .then(response => {
                        users.value = response.data.data;
                        loading.value = false;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
                })
              }
            })

            
            })
            .catch(function(error) {
                console.log(error);
            });

    

          
        }

        const showModal = (payload) => {
            index(payload)
        }

        const onChange = (payload) => {
        const { page } = toRefs(form);
        const { section } = toRefs(form);
        page.value = payload;
        section.value =   selected_section.value;
        filter(form);
        };

    const onShowLimit = (current, pageSize) => {
      const { limit } = toRefs(form);
      const { page } = toRefs(form);
      const { section } = toRefs(form);
      page.value = 1;
      limit.value = pageSize;
      section.value =   selected_section.value;
      filter(form);
    };

    function createDebounce() {
      let timeout = null;
      return function (fnc, delayMs) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const { page } = toRefs(form);
          const { section } = toRefs(form);
          page.value = 1;
          section.value =   selected_section.value;
          filter(form);
        }, delayMs || 500);
      };
    }
        const filter = (payload) => {
        indexAction(payload)
    }
        const editRecord = (record) => {
            router.push({path: '/edit/students/' + record.id,
            query: {archive: 'false'}
            })
    }
    onMounted(index)

    return {
        onChange,
        onShowLimit,
        debounce: createDebounce(),
        editRecord,
        showModal,
        changeBTN,
        ...toRefs(state),
      onSelectChange,

        form,
        users,
        loading,
        columns,
        isSelected,
        hasSelected,
      
    }
}
})

   
</script>

<style scoped>
.ant-table-small .ant-table-thead > tr > th {
    background-color: #bb1111;
}
.search {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  align-content: space-between;
}



</style>