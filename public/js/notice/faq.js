$(function () {
    if($("body").attr('data-device') == 'pc') {
        $("#searchValue").on('keyup', function () {
            if ($(this).val() != '') {
                $(".btn-close").removeClass('none');
            } else {
                $(".btn-close").addClass('none');
            }
        });

        $(".btn-close").on('click', function () {
            $("#searchValue").val('');
            $(".btn-close").addClass('none');
            searchFaq();
        });
    } else {
        $('#searchValue').on('click',function () {
            if($('.notice-content').css('display') == 'block') {
                let html = '';
                let recentSearchWord = lang('최근검색어', 'Recent search word', '最近検索語', '最近搜索詞');
                let search = lang('검색', 'Search', '検索', '搜索');
                $(this).addClass('active')
                $('.account-section').children().attr('href','/notice/customer/faq')
                $('.account-section').contents()[2].textContent = search
                $('.notice-content').hide();
                html += '<div class="recent-search-box"><div>' + recentSearchWord + '</div><div class="recent-word-list"></div></div>'
        
                $('.search-div').parent().append(html)
                loadRecentWord();
            }
        })
    }
});

function openAnswer(obj) {
    if($("body").attr('data-device') == 'pc') {
        $(obj).closest('.faq-li').toggleClass('active');
        $(obj).closest('.faq-li').find('.faq-a').slideToggle(400, 'linear');
    } else {
        $(obj).next().toggleClass('active');
        if($(obj).next().hasClass('active')) {
            $(obj).children('.xi-angle-down-min').attr('class','xi-angle-up-min')
        } else {
            $(obj).children('.xi-angle-up-min').attr('class','xi-angle-down-min')
        }
        $(obj).next().slideToggle(400, 'linear');
    }
}

// 자주 묻는 질문 검색
function searchFaq() {
    const searchValue = $("#searchValue").val();
    const country = $("body").attr('data-lang');

    let url = '/src/controller/faq/getFaqList.php';

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        data:{'search':searchValue},
        success: function (data) {
            
            if($("body").attr('data-device') == 'pc') {
                if (data.resultCode == 10) {
                    let value = data.value;
                    let faqClassName = "faq-li";
                    let faqBoxClass = "faq-box";
                    let answerVisible = '';
                    if (searchValue != '') {
                        value = JSON.stringify(value);
                        let replace = new RegExp(searchValue, "gi");
                        value = value.replace(replace, '<span class=\'faq-search\'>' + searchValue + '</span>');
                        value = JSON.parse(value);
                        faqClassName += ' active';
                        faqBoxClass += ' search-faq-box';
                        answerVisible = 'style="display:block;"';
                    }
                    let html = '';
                    if (searchValue) {
                        html += '<div class="search-title">' +
                            lang(
                                '<span>‘' + searchValue + '’</span>에 대한 검색결과',
                                'Search results for <span>‘' + searchValue + '’</span>',
                                '<span>‘' + searchValue + '’</span>の検索結果',
                                '<span>‘' + searchValue + '’</span>搜索结果') + '</div>';
                    }

                    for (let i = 0; i < value.length; i++) {
                        let faqList = value[i].faqList;
                        if (faqList.length) {
                            html += '<div class="' + faqBoxClass + '">' +
                                '<div class="faq-header">' + value[i]['fc_name_'+country]+ '</div>' +
                                '<ul class="faq-body">';
                            for (let j = 0; j < faqList.length; j++) {
                                html += '<li class="' + faqClassName + '">' +
                                    '<p class="faq-q" onclick="openAnswer(this)">' + faqList[j]['faq_q_' + country] + '</p>' +
                                    '<div class="faq-a" ' + answerVisible + '>' + faqList[j]['faq_a_' + country] + '</div>' +
                                    '</li>';
                            }
                            html += '</ul>' +
                                '</div>';
                        }
                    }
                    $(".notice-content").html(html);
                }
            } else {
                let noticeTitle = lang('고객센터', 'Support', 'サポート','客户中心');
                $('.recent-search-box').remove();
                $('.notice-content').show();
                $('.search-faq-box').remove();
                $('#searchValue').remove('active')
                $('.account-section').contents()[2].textContent = noticeTitle;

                // 최근검색어 저장
                if(searchValue!=''){
                    const newId = Number(recentWordList.length) + 1;
                    const recentWordObj = {
                        text: searchValue,
                        id: newId
                    };
                    recentWordList.push(recentWordObj);
                    setCookie('searchWord',JSON.stringify(recentWordList), 1);
                }
            
                if (data.resultCode == 10) {
                    let value = data.value;
                    let faqClassName = "faq-li";
                    let faqBoxClass = "faq-box";
                    if (searchValue != '') {
                        value = JSON.stringify(value);
                        let replace = new RegExp(searchValue, "gi");
                        value = value.replace(replace, '<span class=\'faq-search\'>' + searchValue + '</span>');
                        value = JSON.parse(value);
                        faqBoxClass += ' search-faq-box';
                        answerVisible = 'style="display:block;"';

                        let html = '';
                        for (let i = 0; i < value.length; i++) {
                            let faqList = value[i].faqList;
                            if (faqList.length) {
                                html += '<div class="' + faqBoxClass + '">' + '<div class="faq-body search-body">';
                                for (let j = 0; j < faqList.length; j++) {
                                    html += '<p class="faq-q" onclick="openAnswer(this)">' + 
                                    '<span class="faq-ico"></span><span>'+ faqList[j]['faq_q_' + country] +'</span><i class="xi-angle-down-min"></i></p>' + 
                                    '<div class="' + faqClassName + '">' + faqList[j]['faq_a_' + country] + '</div>'
                                }
                                html += '</div><div style="width:100%;height:9px;background-color:#191919;"></div>';
                            }
                        }
                        $(".notice-content").prepend(html);
                    } 
                }
            }
        }, error: function (a, b, c) {
            console.log(c);
        }
    })

}