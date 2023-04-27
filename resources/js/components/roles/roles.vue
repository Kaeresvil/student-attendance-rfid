<template>
    <div class="conrainer" style="margin:0 2% 0 2%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 15px; font-weight: bold; color: #312d2dd9;">Role List</h4>
                    <div>
                        <button class="btn btn-lg buttonback" @click="this.$router.push('/create/roles')">Add Role</button>
                    </div>
                </div>
                <a-divider />
                
                <!-- <table class="table table-hover table-sm">
                    <thead class="bg-dark text-light">
                        <tr>
                            <th>Grade Level</th>
                            <th>Description</th>
                            <th class="text-center" width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(grade, index ) in itemrole" :key="itemrole.id">
                        <td>{{grade.grade_level}}</td>
                        <td>{{grade.description}}</td>
      
                        <td class="text-center">
                            <router-link :to="{name:'edituser', params: {id:grade.id}}" class="btn btn-warning mx-1">Edit</router-link>
    
                            <button class="btn btn-danger" @click="deletePost(grade.id)">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table> -->

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
                <!-- <a-button type="primary" @click="$emit('create')" v-if="showCreateButton">
                    <template #icon><PlusOutlined /></template> {{ addText }}
                </a-button> -->
                </a-col>
                </a-row>


                <a-table
                    size="small"
                    row-key="id"
                    :columns="columns"
                    :data-source="itemrole.data"
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

                </a-table>





                
            <a-row style="margin: 15px 15px" v-if="true">
                <a-col class="search" span="24">
                <div>
                    <span
                    >{{ itemrole.from }} - {{ itemrole.to }} of
                    {{ itemrole.total }}</span
                    >
                </div>
                <div>
                    <a-pagination
                    size="small"
                    :total="itemrole.total"
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
    const itemrole = ref([])
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
            dataIndex: 'role_name',
            align: 'center'
        },
        {
            title: 'Description',
            dataIndex: 'description',
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
                   axios.get('/api/page/roles', {params: {...payload}})
                    .then(response => {
                        itemrole.value = response.data.data;
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
            router.push({path: '/edit/roles/' + record.id,
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
        itemrole,
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