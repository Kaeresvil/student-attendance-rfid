<template>
    <div class="conrainer" style="margin:0 2% 0 2%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 15px; font-weight: bold; color: #312d2dd9;">Users List</h4>
                    <div>
                        <button class="btn btn-lg buttonback" @click="this.$router.push('/create/users')">Add User</button>
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

                <template v-slot:role="{ record }">
                    <span>{{ record.role.role_name }}</span>
                </template>

                <template v-slot:description="{ record }">
                    <span>{{ record.role.description }}</span>
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

export default defineComponent({
components:{},
setup(){
    const users = ref([])
    const loading = ref(true)
    const router = useRouter()
    const form = reactive({
      page: 1,
      limit: 15,
      search: "",
    });

    const columns = [
        {
            title: 'Name',
            dataIndex: 'name',
        },
        {
            title: 'Email Address',
            dataIndex: 'email',
        },
        {
            title: 'Role Type',
            dataIndex: 'role.role_name',
            slots: { customRender: 'role' },
            filterMultiple:false,
            filters:[
                {
                    text: "Teacher",
                    value: "Teacher"
                },
                {
                    text: "Head Teacher",
                    value: "Head Teacher"
                },
                {
                    text: "Administrator",
                    value: "Administrator"
                },
            ],
            onFilter: (value ,record) =>
            record.role.role_name.indexOf(value) === 0,
        },
        {
            title: 'Description',
            dataIndex: 'role.description',
              slots: { customRender: 'description' },
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
                   axios.get('/api/page/users', {params: {...payload}})
                    .then(response => {
                        users.value = response.data.data;
                        console.log('users',users.value)
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
            router.push({path: '/edit/users/' + record.id,
            query: {archive: 'false'}
            })
    }
    onMounted(index)

    return {
        onChange,
        onShowLimit,
        debounce: createDebounce(),
        editRecord,

        form,
        users,
        loading,
        columns
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