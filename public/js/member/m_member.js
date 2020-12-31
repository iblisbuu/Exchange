$(function(){

    if($('.member-input-group').hasClass('time-limit')) {
        $('.member-input-group.time-limit').children('span').css('bottom','16px');
    }

    $('.member-input-group>input').focus(function(){
        $(this).parent().children('span').css('background-color','#29292b');
        $(this).parent().children().children('i').css('color','#fff')
    })

    $('.member-input-group>input').blur(function(){
        $(this).parent().children('span').css('background-color','#202022');
        if($(this).val() == "") {
            $(this).parent().children().children('i').css('color','')
        }
    })
})