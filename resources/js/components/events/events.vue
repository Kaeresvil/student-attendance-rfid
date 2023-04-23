<template>
    <div class="conrainer" style="margin:0 2% 0 2%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 15px; font-weight: bold; color: #312d2dd9;">Event List</h4>
                    <div>
                        <button class="block uppercase shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="this.$router.push('/create/events')">Add New Event</button>
                    </div>
                </div>

                <a-divider />

                <div>
                    <div style="margin-bottom: 16px">
                    <a-button   class="btn btn-sm buttonshow mr-1" @click="setEvent()">
                        Set Event
                    </a-button>
                    <a-switch @click="setswitch()" :loading="switchloading" checked-children="ON System" un-checked-children="OFF System" v-model:checked="switch_form.isSet" />
                    </div>
                       
                </div>

                <a-row style="margin-bottom: 15px; width: 98%;">
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
                    :data-source="users.data"
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
                        <span>{{ record.section.grade_level.grade_level }}</span>
                    </template>

                    <template v-slot:section="{ record }">
                        <span>{{ record.section.name }}</span>
                    </template>
                    <template v-slot:isSet="{ record }">
                        <a-tag :color="record.isSet === 1 ?  '#0073b7':'#dd4b39'">{{
                            record.isSet === 1 ? "Set" : "No"
                        }}</a-tag>
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
import { defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../axios"
import { useRouter } from 'vue-router'
import Swal from "sweetalert2";

export default defineComponent({
components:{},
setup(){
    const users = ref([])
    const option_events = ref({})
    const loading = ref(true)
    const switchloading = ref(false)
    const isON = ref(true);
    const router = useRouter()
    const form = reactive({
      page: 1,
      limit: 15,
      search: "",
    });
    const switch_form = reactive({
     id: 1,
     isSet: ""
    });

    const columns = [
        {
            title: 'Event Name',
            dataIndex: 'event_name',
        },
        {
            title: 'Set Event',
            dataIndex: 'event_date',
            slots: { customRender: 'isSet' },
            align: 'center'
        },
        {
            title: 'Event Description',
            dataIndex: 'event_description',
        },
        {
            title: 'Action',
            dataIndex: 'action',
            slots: { customRender: 'action' },
            align: 'center'
        },
        ];

        const index = (payload = {page: 1}) => {
        //get grade and section
        axios.get('/api/page/all_events')
            .then(response => {
                console.log('res',response.data)
                response.data.forEach((datum) =>{
                     Object.assign(option_events.value, {[datum.id]: datum.event_name});
                })

            })
            .catch(function(error) {
                console.log(error);
            });
            /// get system switch
            switchloading.value = true
        axios.get('/api/page/switchedit')
            .then(response => {
                switch_form.isSet = response.data.isSet === 1 ? true:false
                switchloading.value = false
            })
            .catch(function(error) {
                console.log(error);
            });


            loading.value = true;
                axios.get('/api/page/event', {params: {...payload}})
                .then(response => {
                    users.value = response.data.data;
                    loading.value = false;
                    console.log(response.data.data);
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
            router.push({path: '/edit/events/' + record.id,
            query: {archive: 'false'}
            })
    }



    const setswitch = () => {
        switchloading.value = true
        console.log('isON', switch_form.isSet)
       
        axios.post(`/api/page/switch/update/${switch_form.id}`,switch_form)
                .then(response => { 
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                        })

                        Toast.fire({
                        icon: 'success',
                        title: 'Attendance System is already set'
                        }) 
                        switchloading.value = false       
                    })
                .catch(function (error) {
                console.log(error)
                });


        
    }
    const setEvent = () => {
        const id = ref({})

   Swal.fire({
   title: 'Set Attendance Event',
   input: 'select',
   inputOptions: option_events.value,
   inputPlaceholder: 'Select event...',
   showCancelButton: true,
   inputValidator: (value) => {
       return new Promise((resolve) => {
        id.event_id = value
        console.log(value)
           axios.post('/api/page/setevent',id)
               .then(response => {

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                    })

                    Toast.fire({
                    icon: 'success',
                    title: 'Event Set Successfully'
                    })

                   resolve()
                onChange()
                       
               })
               .catch(function (error) {
               console.log(error)
               });
       })
     }
   })

   
};

    onMounted(index)

    return {
        onChange,
        onShowLimit,
        debounce: createDebounce(),
        editRecord,
        setEvent,
        setswitch,

        form,
        switch_form,
        isON,
        users,
        loading,
        switchloading,
        columns
    }
}
})

   
</script>

<style scoped>
.ant-table-small .ant-table-thead > tr > th {
    background-color: #bb1111;
}
.ant-switch-checked {
   background-color: #367fa9;
}
.search {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  align-content: space-between;
}



</style>