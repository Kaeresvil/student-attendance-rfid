<template>
    <div @click="preventBlur()" style=" width: 100vw; height: 100vh; margin-top: -85px;">
     <div class="container" style="align-items:center; margin-top: 3%;">
            <div class="row">
                <div class="col-sm-5 justify-content-center pt-4 bucket" >

                    <img class="w-24 " src="/img/RFIDLOGO.png"/>

                    <div class="row  justify-content-center" style="align-items: center; font-weight: 900; font-size: 20px;">
                     {{ continues_date }}
                    </div>
                    <div class="row  justify-content-center" style="margin-top: -15px;  font-weight: 900; font-size: 16px;">
                    {{ "("+continues_day+")" }}
                    </div>
                    <div class="row justify-content-center" style="margin-top: -10px; font-weight: 900; font-size: 28px;">
                     {{ continues_time}}
                    </div>
                    <div class="row justify-content-center">
                        <a-input style="width:90%; text-align: center; font-size: 16px; font-weight: bold;" v-model:value=" form.event_name" :readonly="true"/>
                    </div>
                    <div class="row selector">
                     
                            <h5 style="font-size: 17px; color: white">Choose Log Type Below</h5>

                        <a-radio-group v-model:value="form.attendance_type" button-style="solid">
                            <div class="selectorcolumn">
                                <div class="form-check">
                                    <a-radio-button @click="onChangetimein()"  :value="1">Time in(AM)</a-radio-button>
                                </div>
                                <div class="form-check">
                                    <a-radio-button @click="onChangetimein()"  :value="2">Time in(PM)</a-radio-button>
                                </div>
                            </div>
                            <div class="selectorcolumn">
                                <div class="form-check">
                                    <a-radio-button @click="onChangetimein()"  :value="3">Time out(AM)</a-radio-button>
                                </div>
                                <div class="form-check">
                                    <a-radio-button @click="onChangetimein()"  :value="4">Time out(PM)</a-radio-button>
                                </div>
                            </div>
                        </a-radio-group>
                    </div>
                </div>
                <div v-if="visible_scanner && !visible_notregistered" class="col-sm scanner">
                   
                   <img class="img_scanner" src="/img/scanner.png" alt="">
                    <p class="tapper">Tap your RFID to the Reader</p>
                    <p style="margin-top: -20px;">In case lost of RFID type your LRN below.</p>
                    <input class="code" @input="parseCode()" placeholder="Learner Refence No." ref="focusMe"  type='text' v-model="form.rfid_code" autofocus/>
                  
                </div>
                <div v-if="visible_notregistered && visible_scanner " class="col-sm scanner">
                    <p v-if="!existing" class="notfound">Learner Refence Number not found in the database!</p>
                    <p  v-if="existing" class="notfound">{{ existing_message }}</p>
                  
                </div>
                <div v-if="!visible_notregistered && !visible_scanner " class="col-sm scanner_info" >
                    <div class="row justify-content-center">
                           <h2 style="font-size: 18px; font-weight: bold; margin-left: 30px; margin-top: 20px;">Learner Refence No.</h2> 
                        <div class="LRN" style="border: 1px solid black; width: 90%; font-size: 17px; font-weight: bold;">{{ form.rfid_code }}</div>
                        <!-- <input @keyup="parseCode(form.rfid_code)" autofocus  type='text' v-model="form.rfid_code" style=" background: transparent; width: 90%; font-size: 17px; font-weight: bold;"/> -->
                                        </div>
                    <div class="row">
                            <h2  style="font-size: 22px; font-weight: bold; margin-left: 10px;"><i class="stud_in">Arrive Time:</i> {{ form.time }} </h2>
                    </div>
                    <div class="row">
                        <h2  v-if="form.attendance_type == 1 && isOntime || form.attendance_type == 2 && isOntime" style="font-size: 22px; font-weight: bold; margin-left: 10px;"><i class="stud_in">Remarks:</i> You arrived {{ hours }} hours and {{ minutes }} minutes early. </h2>
                        <h2  v-if="form.attendance_type == 1 && !isOntime || form.attendance_type == 2 && !isOntime" style="font-size: 22px; font-weight: bold; margin-left: 10px;"><i class="stud_in">Remarks:</i> You arrived {{ hours }} hours and {{ minutes }} minutes late. </h2>
                    </div>
                    <div class="row justify-content-center">
                      <div style="background-color:#032b5e; color: white; text-align: center; width: 50%; margin-bottom: -13px;font-size: 16px;">STUDENT DETAILS</div>
                      <div style="border: 1px solid #032b5e; width: 90%; padding-top: 20px; margin-bottom: 5px;">
                        <h2 class="stud_details"><i class="stud_in">Name: </i> {{ form.name }}</h2>
                        <h2 class="stud_details"><i class="stud_in">Grade: </i> {{ form.grade }}</h2>
                        <h2 class="stud_details"><i class="stud_in">Section: </i> {{ form.section }}</h2>
                      </div>
                    </div>
                    <div class="row justify-content-center">
                      <div @click="sample()" style="background-color:#032b5e; color: white; text-align: center; width: 90%;font-size: 22px;">YOU HAVE SUCCESSFULLY TIME {{form.attendance_type === 1 || form.attendance_type === 2 ?'IN':'OUT'}}!</div>
                    </div>
                </div>

         </div>
     </div>
     
    </div>
</template>
<script >
import { defineComponent, ref, onMounted, reactive } from 'vue';
import moment from "moment"
import { useRoute, useRouter} from 'vue-router'
import router from '../../router';

export default defineComponent({
components:{},
setup(){
    const visible_scanner = ref(true)
    const visible_notregistered = ref(false)
    const existing = ref(false)
    const continues_time = ref()
    const existing_message = ref()
    const continues_date = ref()
    const continues_day = ref()
    const router = useRouter()
    const timeIn = ref()
    const form = reactive({
        id: "",
        rfid_code: "",
        stud_id: "",
        name: "",
        grade: "",
        section: "",
        section_id: "",
        event_name: "",
        event_id: "",
        current_grade_id:"",
        current_section_id:"",
        attendance_type: "",
        attendance_date:"",
        time:"",
    })
 
    const autofocusFields = (e)=>{
        var el = document.getElementsByClassName("code")[0];
        hideKeyboard(el);

        function hideKeyboard(el) {
        setTimeout(function() {
            el.focus(); //focus on the input
        }, 100);
        }
    }
    const sample = (e)=>{
console.log(form)
    }
    const onChangetimein = ()=>{
        autofocusFields()
    }
    const preventBlur = ()=>{
        autofocusFields()
    }

    const hours = ref();
    const minutes = ref();
    const isOntime = ref(false);
    
    const parseCode = (e)=>{
        
        form.attendance_date =     moment().format("YYYY-MM-DD h:mm:ss a");
        form.time =  moment().format("LTS");

        var now =  moment().format("YYYY-MM-DD HH:mm:ss");
        var timeHours =  moment().hour();
        var timeMinutes =  moment().hour();
        var time_in_am =  moment().format("YYYY-MM-DD 07:45:ss a");
        var time_in_pm =  moment().format("YYYY-MM-DD 13:15:ss a");

       var dur_am = moment.utc(moment(now,"YYYY-MM-DD HH:mm:ss").diff(moment(time_in_am,"YYYY-MM-DD HH:mm:ss")))
       var dur_pm = moment.utc(moment(now,"YYYY-MM-DD HH:mm:ss").diff(moment(time_in_pm,"YYYY-MM-DD HH:mm:ss")))

    if(form.attendance_type === 1){
        hours.value = moment(dur_am).hour()
        minutes.value = moment(dur_am).minutes()
       if (timeHours <= 7 && timeMinutes <= 15){
        isOntime.value = true
       }
    }
    if(form.attendance_type === 2){
        hours.value = moment(dur_pm).hour()
        minutes.value = moment(dur_pm).minutes()
       if (timeHours <= 13 && timeMinutes <= 15){
        isOntime.value = true
       }
    }

        console.log(form.attendance_type);
        console.log(hours.value);
        console.log(minutes.value);
        console.log(isOntime.value);
        if(form.rfid_code.length == 10){
            axios.get(`/api/getStudent/${form.rfid_code}`)
            .then(response => {
                response.data.forEach((datum) =>{
                    form.stud_id =datum.id
                    form.name =datum.name
                    form.section =datum.section.name
                    form.section_id =datum.section.id
                    form.grade =datum.section.grade_level.grade_level
                })
                if(form.name === '' && form.section === '' && form.grade === ''){
                    visible_notregistered.value = true
                    setTimeout(() => {
                        visible_notregistered.value = false
                        form.rfid_code = ""
                        form.stud_id = ""
                        form.name = ""
                        form.section = ""
                        form.grade = ""
                        }, 3000)
                }else{
                 
                    axios.post('/api/attendance',form)
                    .then(response => { 
                       if(response.data.status == 'existing'){
                            existing_message.value = response.data.message,
                            existing.value = true
                            visible_notregistered.value = true
                            setTimeout(() => {
                            visible_notregistered.value = false
                            existing.value = false
                            form.rfid_code = ""
                            form.stud_id = ""
                            form.name = ""
                            form.section = ""
                            form.grade = ""
                            }, 3000)
                          
                        }else if(response.data.status == 'success'){
                            visible_scanner.value = false   
                            setTimeout(() => { 
                            visible_scanner.value = true
                            form.rfid_code = "",
                            form.stud_id = ""
                            form.name = ""
                            form.section = ""
                            form.grade = ""
                            }, 3000)
                        }
                    })

                   

                }     
             

                })
                .catch(function(error) {
                    console.log(error);
                });
        }

    }

    onMounted(() =>{ 
        form.attendance_type = 1
        setInterval(() => {
            continues_time.value =moment().format('LTS');
            continues_date.value =moment().format('LL');
            continues_day.value =moment().format('dddd');
            autofocusFields() 
        }, 100)
      //get grade and section
      axios.get('/api/getevent')
            .then(response => {       
                response.data.data.forEach((data) => {
                    form.event_name = data.event_name
                    form.event_id = data.id
                })
            })
            .catch(function(error) {
                console.log(error);
            });
            axios.get('/api/getswitch')
                .then(response => {
                   response.data.isSet === 0 ? router.push('/'):console.log(response)
                })
                .catch(function(error) {
                    console.log(error);
                }); 
                

    
    
    })
    return {
        onChangetimein,
        parseCode,
        sample,
        preventBlur,
        continues_time,
        continues_date,
        continues_day,
        timeIn,
        form,
        visible_scanner,
        visible_notregistered,
        existing_message,
        existing,
        isOntime,
        hours,
        minutes
    }
}
})


</script>



<style scoped>
.stud_in{
    font-size: 20px;
    font-weight: 500;
}
.stud_details{
    font-size: 25px;
    font-weight: bold;
}
.selectorcolumn {
  float: left;
  width: 50%;
  padding: 10px;
}

img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 30%;
  margin-bottom: 15px;
}
.code{
    background: transparent; 
    width: 90%; 
    font-size: 17px;
    font-weight: bold;
    text-align: center;
    border: 2px solid #6a6a6a;
}

.container{
    width: 68%;
    position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(0, -50%);
  transform: translate(-50%, -50%);
}

.scanner{
    border: 2px solid #959595;
    text-align: center;
}
.scanner_info{
    border: 2px solid #959595;
}
.img_scanner{
    width: 350px;
    height: 300px;

}
.tapper{
    font-size: 25px;
    font-weight: 900;
    margin-top: -10px;
}
.notfound{
    font-size: 25px;
    font-weight: 900;
    margin-top: 34%;
    
    }
.selector{
    position: relative;
    border: 1px solid rgb(0, 0, 0);
    background-color: #022859;
    color: white;
    width: 95%;
    margin-left: 2.5%;
    text-align: center;
    align-items: center;
}
.bucket{
    border: 2px solid rgb(0, 0, 0);
    background-color: #FFA800;
}
.row{
    margin-bottom: 15px;
}
.form-check{
    background-color: #fcb527;
    border-radius: 6px;
    color: black;
    font-weight: bolder;
    margin-bottom: 15px;
    text-align: center;
}

.form-check-input:checked {
    background-color: #f70000;
    border-color: #fa0000;
}
.form-check-input {
    align-self: center !important;
}


label{
    font-size: small;
}

.row-centered {
    text-align: center;
}
h5{
   margin-top: 5px;
}

.form-check .form-check-input {
    margin-right: 5px;
    font-size: 17px;
    margin-top: 2px;
}



</style>