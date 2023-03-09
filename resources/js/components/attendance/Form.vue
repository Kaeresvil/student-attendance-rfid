ar<template>
    <div style=" width: 100vw; height: auto;">
     <div class="container" style="align-items:center; margin-top: 3%;">
            <div class="row">
                <div class="col-sm-5 justify-content-center pt-4 bucket" >

                    <img src="/img/logo.png" alt="">

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
                        <select name="cars" id="cars" style="width: 90%;">
                        <option value="volvo">Class Attendance</option>
                        <option value="saab">Event Attendance</option>
                        </select>
                    </div>
                    <div class="row selector">
                     
                            <h5 style="font-size: 15px;">Choose Log Type Below</h5>

                            <div class="col-sm justify-content-center">
                                <div class="form-check">
                                <input @click="onChangetimein('timeinAM')" class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                <label class="form-check-label" for="flexRadioDefault1">
                                   TIME IN (AM)
                                </label>
                                </div>
                                <div class="form-check">
                                <input  @click="onChangetimein('timeinPM')" class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    TIME IN (PM)
                                </label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-check">
                                <input   @click="onChangetimein('timeoutAM')" class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    TIME OUT (AM)
                                </label>
                                </div>
                                <div class="form-check">
                                <input @click="onChangetimein('timeoutPM')" class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault5">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    TIME OUT (PM)
                                </label>
                                </div>
                            </div>
                    </div>
                </div>
                <div v-if="visible_scanner" class="col-sm scanner">
                   
                   <img class="img_scanner" src="/img/scanner.png" alt="">
                    <p class="tapper">Tap your RFID to the Reader</p>
                    <p style="margin-top: -20px;">In case lost of RFID type your LRN below.</p>
                    <input class="code" @keyup="parseCode(form.rfid_code)" placeholder="Learner Refence No." ref="focusMe"  type='text' v-model="form.rfid_code" autofocus/>
                  
                </div>
                <div v-else class="col-sm scanner_info" >
                    <div class="row justify-content-center">
                           <h2 style="font-size: 18px; font-weight: bold; margin-left: 30px; margin-top: 20px;">Learner Refence No.</h2> 
                        <div class="LRN" style="border: 1px solid black; width: 90%; font-size: 17px; font-weight: bold;">{{ form.rfid_code }}</div>
                        <!-- <input @keyup="parseCode(form.rfid_code)" autofocus  type='text' v-model="form.rfid_code" style=" background: transparent; width: 90%; font-size: 17px; font-weight: bold;"/> -->
                                        </div>
                    <div class="row">
                            <h2  style="font-size: 18px; font-weight: bold; margin-left: 10px;">Arrive Time: {{ timeIn }} </h2>
                    </div>
                    <div class="row">
                        <h2  style="font-size: 18px; font-weight: bold; margin-left: 10px;">Remarks: You arrived 29 minutes and 30 seconds early. </h2>
                    </div>
                    <div class="row justify-content-center">
                      <div style="background-color:#032b5e; color: white; text-align: center; width: 50%; margin-bottom: -13px;font-size: 16px;">STUDENT DETAILS</div>
                      <div style="border: 1px solid #032b5e; width: 90%; padding-top: 20px; margin-bottom: 5px;">
                        <h5>Name: Kaeresvil O. Arellano</h5>
                        <h5>Grade: 12</h5>
                        <h5>Section: TVL-A</h5>
                      </div>
                    </div>
                    <div class="row justify-content-center">
                      <div style="background-color:#032b5e; color: white; text-align: center; width: 90%;font-size: 22px;">YOU HAVE SUCCESSFULLY LOGGED IN!</div>
                    </div>
                </div>

         </div>
     </div>
     
    </div>
</template>
<script >
import { defineComponent, ref, onMounted, reactive } from 'vue';
import moment from "moment"

export default defineComponent({
components:{},
setup(){
    const visible_scanner = ref(true)
    const continues_time = ref()
    const continues_date = ref()
    const continues_day = ref()
    const timeIn = ref()
    const form = reactive({
        id: "",
        rfid_code: "",
        first_name:"",
        middle_name:"",
        last_name:"",
        grade:"",
        section:"",
    })
 
    const onChangetimein = (e)=>{
console.log(e)
    }
    const parseCode = (e)=>{
       
        timeIn.value =   continues_time.value;
        setTimeout(() => {
            visible_scanner.value = false
        timeIn.value = "";
        }, 1000)
        setTimeout(() => {
            visible_scanner.value = true
            form.rfid_code = ""
        }, 4000)
console.log( e);

    }

    onMounted(() =>{ 
        setInterval(() => {
            continues_time.value =moment().format('LTS');
            continues_date.value =moment().format('LL');
            continues_day.value =moment().format('dddd'); 
        }, 1000)
    //    document.getElementsByClassName('code')
       var el = document.getElementsByClassName("code")[0];
        hideKeyboard(el);

        function hideKeyboard(el) {
        setTimeout(function() {
            el.blur(); //close the keyboard
            el.focus(); //focus on the input
        }, 100);
        }
    })
    return {
        onChangetimein,
        parseCode,
        continues_time,
        continues_date,
        continues_day,
        timeIn,
        form,
        visible_scanner
    }
}
})


</script>



<style scoped>
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
    align-self: center;
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
    margin-right: 4px;
    font-size: 17px;
    margin-top: 2px;
}


</style>