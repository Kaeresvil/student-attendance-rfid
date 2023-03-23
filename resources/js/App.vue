<template>
<div>
        <router-view></router-view>
</div>
</template>

<style>
.div_container{
  max-width: 98%;
    position: absolute;
  left: 50%;
  transform: translate(-50%, -50%);
  margin-top: 9%;
}

.ant-table-row-cell-break-word{
  padding: 0 !important;
}

.ant-table-small .ant-table-thead > tr > th {
    background-color: #121212 !important;
    color: white !important;
}

.ant-table-tbody > tr > td {
    border: 1px solid #dcdcdc;

}
.card_form{
  width: 70%;
  margin: auto;
}

.buttonshow {
  background-color: #3c8dbc !important;
  border-color: #367fa9 !important;
  color: white !important;
}
.buttondelete {
  background-color: #a71616 !important;
  border-color: #a70909 !important;
  color: white !important;
}

.ant-btn[disabled] {
    background-color: #a8a8a8 !important;
}

.ant-radio-group-solid .ant-radio-button-wrapper-checked:not(.ant-radio-button-wrapper-disabled) {
    color: #fff;
    background: #a71616 !important;
    border-color: #a71616 !important;
}

.ant-radio-button-wrapper:hover {
    color: #FFA800 !important;
}




</style>