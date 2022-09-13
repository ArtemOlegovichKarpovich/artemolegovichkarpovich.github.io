/*function isZamena(){
    if($('#zamenaSelect').attr("disabled")){
        $('#zamenaSelect').removeAttr("disabled");
    }
    else{
        $('#zamenaSelect').prop('disabled');
    }
}*/
function isZamena(id){
    chbtn_name = '#zamenaCheck' + id;
    select_name = '#zamenaSelect' + id;
    if($(chbtn_name).prop("checked")){
        $(select_name).removeAttr("disabled");
    }
    else{
    // $('#zamenaSelect').prop('disabled');
        $(select_name).attr("disabled", true);
    }
}
function getWeek(){
    var select = document.getElementById("week");
    var value = select.value;
}

function actWeekChange(step) {

        $("#week")[0].selectedIndex = $("#week")[0].selectedIndex + step;
        $("#headForm2").submit();
        //actDayChange();
}
