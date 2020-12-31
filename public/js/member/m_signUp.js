// 동의하기
function agreeClick(id) {
    if($("#"+id).is(":checked")){
        $("#"+id).prop('checked', true)
        $(".xi-check-" + id).removeClass('none')
    } else {
        $("#"+id).prop('checked', false)
        $(".xi-check-" + id).addClass('none')
    }
    
    joinValid();
}