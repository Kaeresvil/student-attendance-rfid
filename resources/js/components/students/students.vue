<template>
    <div class="conrainer" style="margin:0 2% 0 2%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h5 class="card-title">Users List</h5>
                    <div>
                        <button class="block uppercase mx-auto shadow bg-emerald-800 hover:bg-emerald-700 focus:shadow-outline focus:outline-none text-white text-sm py-2 px-4 rounded" @click="this.$router.push('/students/register')">Register New Student</button>
                    </div>
                </div>
                
                <table class="table table-hover table-sm">
                    <thead class="bg-dark text-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Name</th>
                            <th>LRN #</th>
                            <th>Section</th>
                            <th class="text-center" width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
  
                        </tr>
                    </tbody>
                </table>
        
        
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>