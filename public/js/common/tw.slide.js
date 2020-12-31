/*
----- 제작자 : 고태욱
----- 제작일 : 2018.05.03
----- Ver 1.0

----- 슬라이드 종류
basic : 기본적인 슬라이드이며 모바일에선 swipe기능이 가능합니다. (2018.05.04 최종)
opacity : 슬라이드 요소가 겹쳐있으며 이동할때 opacity가 발생합니다. (2018.05.04 최종)
list : 슬라이드 요소가 리스트형식으로 출력됩니다. (2018.05.14 최종)

----- 슬라이드 사용법
$(선택자).tw_slide({
	mode:'basic', //  슬라이드 종류 참고
	navi:true, // 네비게이션 (기본값 : false)
	auto:true, // 자동 슬라이드 (기본값 : false)
	control:true, // 오른쪽, 왼쪽 버튼 (기본값 : false)
	loop:true, // 처음 또는 마지막으로 되돌아가지 않고 자연스럽게 계속넘어갑니다. opacity는 아무 차이가 없습니다. (기본값 : false)
	speed:1000, // 슬라이드 바뀌는 속도 (1000 = 1초)
	time:6000 // 슬라이드 딜레이 (1000 = 1초)
});
*/
(function($) {
    $.fn.tw_slide = function(option){
        var t = $($(this)[0]);
        var mode = option.mode,
            navi = option.navi,
            control = option.control,
            speed = option.speed,
            time = option.time,
            auto = option.auto,
            loop = option.loop,
            next_width = option.next_width,
            pause = option.pause;
        var a = t.children('ul'),
            b = t.children('ul').children('li'),
            slide_var = '',
            l = b.length,
            w = t.width(),
            h = t.height(),
            z = true,
            ls = 0;
        var f = function(){
            var s = a.attr('data-index');
            if(mode == 'list') ls = ls - b.eq(s).width();
            else if(mode == 'next')  ls = ls - b.eq(s).width();

            if(mode == 'basic' && loop == true) s++;
            else s == l * 1 - 1 ? s = 0 : s++;

            t.find('ul + .navi_warp > button').removeClass('select').eq(s).addClass('select');
            b.removeClass('select').eq(s).addClass('select');
            a.attr('data-index', s);

            if(mode == 'basic') a.css({'transition': 'transform '+ speed + 'ms ease-in-out', 'transform':'translateX(' + s * w * -1 + 'px)'});
            else if(mode == 'opacity') b.css('opacity', 0).eq(s).css('opacity', 1);
            else if(mode == 'list') a.css({'transition': 'transform '+ speed + 'ms', 'transform':'translateX(' + ls + 'px)'});
            else if(mode == 'next')  a.css({'transition': 'transform '+ speed + 'ms', 'transform':'translateX(' + ls + 'px)'});

            if(s == l && mode == 'basic' && loop == true){
                s = 0;
                t.find('ul + .navi_warp > button').eq(s).addClass('select');
                b.eq(s).addClass('select');
                a.attr('data-index', s).delay(speed).queue(function(){
                    a.css({'transition':'transform 0ms', 'transform':'translateX(0px)'});
                    $(this).dequeue();
                });
            } else if(mode == 'list' && b.eq(0).find('img').attr('src') == b.eq(s).find('img').attr('src')){
                s = 0, ls = 0;
                a.attr('data-index', s).delay(speed).queue(function(){
                    a.css({'transition':'transform 0ms', 'transform':'translateX(0px)'});
                    $(this).dequeue();
                });
            }
        }

        a.attr('data-index', '0');
        b.eq(0).addClass('select');

        if(mode == 'basic'){
            var y = new Array(),
                d = new Array(),
                p = new Array(),
                r = new Array(),
                m = 0,
                lt = t.length - 1;

            for(var i = 0; i <= lt; i++) y[i] = 0, d[i] = 0, p[i] = 0, r[i] = 0;

            if(loop == true) a.append('<li style="width:'+w+'px; height:'+h+'px; float:left; position:relative;">' + t.find('ul > li:first-child').html() + '</li>');

            a.css({
                'transition':'transform ' + time + 'ms',
                'transform':'translateX(0px)',
                'width': ((l+1) * 100) + '%',
                'display':'inline-block',
                'vertical-align':'top'
            });
            if(loop == true) a.css('width', (l*1+2) * 100 + '%');
            b.css({'width':w+'px', 'height':h+'px', 'float':'left', 'position':'relative'});

            a.bind('touchstart',function(event){
                var e = event.originalEvent;
                clearInterval(slide_var);
                m = $(this).parent().index();
                $(this).css('transition','transform 0ms');
                y[m] = e.targetTouches[0].pageX;
                p[m] = a.attr('data-index') * -w;
            });

            a.bind('touchmove', function(event){
                var e = event.originalEvent;
                r[m] =  e.targetTouches[0].pageX - 5 - y[m];
                d[m] = p[m] + e.targetTouches[0].pageX - 5 - y[m];

                if(r[m] > 0 && a.attr('data-index') == 0 && loop == true) $(this).css('transform', 'translateX(' + (d[m] - w * l) + 'px)');
                else $(this).css('transform', 'translateX(' + d[m] + 'px)');

                if(y[m] - e.targetTouches[0].pageX < 0) event.preventDefault();
            });

            a.bind('touchend', function(event){
                var c = Math.ceil(p[m] / w);
                var last_move = $(this).find('li').last().index() * -1 * w;
                var move_lat = Math.ceil(r[m] / w * 100);

                if(d[m] > 0) p[m] = 0;
                else if(last_move > d[m]) p[m] = last_move;
                else if(move_lat < -10) p[m] = p[m] - w;
                else if(move_lat > 10) p[m] = p[m] + w;
                else {
                    p[m] = d[m];
                    p[m] = c * w;
                }
                var s = Math.ceil(p[m] / w) * -1;
                if(d[m] > 0 && loop == true) $(this).css({'transition':'transform 500ms', 'transform':'translateX(' + (l - 1) * w * -1 + 'px)'});
                else $(this).css({'transition':'transform 500ms', 'transform':'translateX(' + p[m] + 'px)'});
                r[m] = 0;

                if(s == l && loop == true){
                    s = 0;
                    t.find('ul + .navi_warp > button').eq(s).addClass('select');
                    b.eq(s).addClass('select');
                    a.attr('data-index', s).delay(500).queue(function(){
                        a.css({'transition':'transform 0ms', 'transform':'translateX(0px)'});
                        $(this).dequeue();
                    });
                }

                a.attr('data-index', s);
                b.removeClass('select').eq(s).addClass('select');
                t.find('ul + .navi_warp > button').removeClass('select').eq(s).addClass('select');

                if(auto == true) slide_var = setInterval(f, time);
            });

            $(window).resize(function(){
                w = t.width(), h = t.height();
                b.css({'width':w+'px', 'height':h+'px', 'float':'left', 'position':'relative', 'transition':'0ms'});
                a.css({'transition':'transform 0ms', 'transform':'translateX(' + a.attr('data-index') * w * -1 + 'px)'});
            });
        } else if(mode == 'opacity'){
            a.css({
                'width': '100%',
                'height':'100%',
                'position':'relative'
            });
            b.css({
                'width':w+'px',
                'height':'100%',
                'float':'left',
                'position':'absolute',
                'top':0,
                'left':0,
                'transition':'opacity ' + speed + 'ms',
                'z-index':1
            });
            t.find('ul li > *').css({
                'transform':'translate(-50%, -50%)',
                'top':'50%',
                'left':'50%',
                'position':'absolute'
            });
            $(window).resize(function(){
                w = t.width();
                b.css({
                    'width':w+'px',
                    'height':'100%',
                    'float':'left',
                    'position':'absolute',
                    'top':0,
                    'left':0,
                    'transition':'opacity ' + speed + 'ms',
                    'z-index':1
                });
            });
        } else if(mode == 'list'){
            for(var i = 0; i<l; i++){
                var j = i + l;
                a.append('<li style="width:' + b.eq(i).find('> *').width() + 'px; height:' + h + 'px; float:left; position:relative;">' + b.eq(i).html() + "</li>");
            }
            for(var k = 0; k < b.length; k++) b.eq(k).css({'width':b.eq(k).find('> *').width(), 'height': h + 'px', 'float':'left', 'position':'relative'});
        } else if(mode == 'next'){
        }

        if(auto == true) slide_var = setInterval(f, time);

        if($.trim(pause) != ''){
            $(pause).click(function(){
                if($(this).hasClass('stop'))
                    slide_var = setInterval(f, time);
                else
                    clearInterval(slide_var);

                $(this).toggleClass('stop');
            });
        }

        if(control == true){
            t.find('ul').before('<span class="left"></span><span class="right"></span>');

            t.find('> span').click(function(){
                if(z == true){
                    clearTimeout(z);
                    z = setTimeout(function(){ z = true; }, speed);
                    clearInterval(slide_var);
                    var s = a.attr('data-index');
                    if($(this).hasClass('left')) s == 0 ? s = l * 1 - 1 : s--;
                    else {
                        if((mode == 'basic' && loop == true) || mode == 'list') s++;
                        else s == l * 1 - 1 ? s = 0 : s++;
                    }

                    a.attr('data-index', s);
                    b.removeClass('select').eq(s).addClass('select');
                    t.find('ul + .navi_warp > button').removeClass('select').eq(s).addClass('select');

                    if(mode == 'basic' && loop == true && s == l * 1 - 1 && $(this).hasClass('left')){
                        a.css({'transition':'transform 0ms', 'transform':'translateX(' + l * w * -1 + 'px)'}).delay(1).queue(function(){
                            a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + s * w * -1 + 'px)'});
                            $( this ).dequeue();
                        });
                    } else if(mode == 'basic') a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + s * w * -1 + 'px)'});
                    else if(mode == 'list'){
                        if($(this).hasClass('left')){
                            if(s + 1 == l){
                                ls = 0;
                                for(var i = 0; i<l; i++) ls = ls - b.eq(i).find('> *').width();
                                a.css({'transition':'transform 0ms', 'transform':'translateX(' + ls + 'px)'}).delay(1).queue(function(){
                                    ls = ls + b.eq(s - 1).find('> *').width();
                                    a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + ls + 'px)'});
                                    $( this ).dequeue();
                                });
                            } else {
                                ls = ls + b.eq(s).width();
                                a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + ls + 'px)'});
                            }
                        } else {
                            ls = ls - b.eq(s - 1).width();
                            if(s == l){
                                a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + ls + 'px)'}).delay(speed).queue(function(){
                                    a.css({'transition':'transform 0ms', 'transform':'translateX(0px)'});
                                    $( this ).dequeue();
                                });
                                s = 0, ls = 0;
                                a.attr('data-index', s);
                                b.removeClass('select').eq(s).addClass('select');
                                t.find('ul + .navi_warp > button').removeClass('select').eq(s).addClass('select');
                            } else a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + ls + 'px)'});
                        }
                    } else if(mode == 'next'){
                        if($(this).hasClass('left')){
                            ls = ls + next_width;
                            if(s + 1 == l) alert('첫번째 페이지입니다.');
                            else a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + ls + 'px)'});
                        } else {
                            ls = ls - next_width;
                            if(s + 5 == l) alert('마지막 페이지입니다.');
                            else a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + ls + 'px)'});
                        }
                    }

                    if(mode == 'basic' && loop == true){
                        if(s == l && $(this).hasClass('right')){
                            s = 0;
                            t.find('ul + .navi_warp > button').eq(s).addClass('select');
                            b.eq(s).addClass('select');
                            a.attr('data-index', s).delay(speed).queue(function(){
                                a.css({'transition':'transform 0ms', 'transform':'translateX(0px)'});
                                $(this).dequeue();
                            });
                        }
                    }

                    if(auto == true) slide_var = setInterval(f, time);
                }
            });
        }

        if(navi == true){
            var navi_con = '';
            for(var i = 0; i < l; i++) navi_con += '<button' + (i == 0 ? ' class="select"' : '') + '></button>';
            t.find('ul').after('<div class="navi_warp">' + navi_con + '</div>');

            t.find('ul + .navi_warp > button').click(function(){
                if(z == true){
                    if($(this).hasClass('select') == false){
                        clearTimeout(z);
                        z = setTimeout(function(){ z = true; }, speed);
                        clearInterval(slide_var);
                        var s = $(this).index();
                        t.find('ul + .navi_warp > button').removeClass('select').eq(s).addClass('select');
                        b.removeClass('select').eq(s).addClass('select');
                        a.attr('data-index', s);
                        if(mode == 'basic') a.css({'transition':'transform ' + speed + 'ms cubic-bezier(0.98, 0, 0.22, 0.98)', 'transform':'translateX(' + s * w * -1 + 'px)'});
                        else if(mode == 'list'){
                            ls = 0;
                            for(var i = 0; i<s; i++) ls = ls - b.eq(i).find('> *').width();
                            a.css({'transition':'transform ' + speed + 'ms', 'transform':'translateX(' + ls + 'px)'});
                        }

                        if(auto == true) slide_var = setInterval(f, time);
                    }
                }
                return false;
            });

            t.find('ul + .navi_warp').click(function(e){
                if($(this).width()*1 + 75 - e.offsetX < 41){
                    if($(this).hasClass('stop') == false){
                        clearTimeout(z);
                        z = setTimeout(function(){ z = true; }, speed);
                        clearInterval(slide_var);
                    } else  slide_var = setInterval(f, time);
                }
                return false;
            });
        }
    }
}(jQuery));

(function(){
    var originalAddClassMethod = jQuery.fn.addClass;

    jQuery.fn.addClass = function(){
        var result = originalAddClassMethod.apply( this, arguments );

        jQuery(this).trigger('cssClassChanged');

        return result;
    }
})();