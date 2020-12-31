$(function () {
    const path = window.location.pathname;
    const pathArray = path.split('/').filter(function (el) {
        return el != ''
    });
    let accountType = (pathArray[2] != null) ? pathArray[2] : 'info';
    // 탭
    $('.account-tap > a').removeClass('active')
    $('.' + accountType + '-tab').addClass('active')

    // 내용
    $('#' + accountType + '-content').removeClass('none')

    getAccess()
    $(".access-btn").click(function () {
        $(".access-btn").toggleClass('close');
        $(".access-table-div").slideToggle('fast');
        if($("body").attr('data-device')=='mobile') {
            $(".access-btn").children().toggleClass('xi-angle-up');
            $(".access-btn").children().toggleClass('xi-angle-down');
        }
    });

    // 마케팅 수신동의 변경
    $("input[name=marketing]").change(function () {

        let memberMarketing = $(this).val();
        let memberId = $("#memberId").text();

        const param = {
            mb_id: memberId,
            mb_marketing: memberMarketing,
        }

        // 모바일
        if($("body").attr('data-device')=='mobile') {
            if($('input[name=marketing]').is(":checked")==true) {
                $('.xi-check-marketing').removeClass('none')
            } else {
                $('.xi-check-marketing').addClass('none')
            }
        }

        $.ajax({
            url: '/src/controller/member/change_member_info.php',
            dataType: 'json',
            type: 'POST',
            data: JSON.stringify(param),
            success: function (data) {
                const resultCode = data.resultCode;
                if (resultCode == 10) {
                } else {
                    console.log('마켓팅 수신 동의 ERROR');
                }
            }, error: function (a, b, c) {
                console.error(c);
            }
        });
    });

})

// 접근권한 출력
function getAccess(pageNum) {
    pageNum = (pageNum == undefined) ? 1 : pageNum;

    let memberId = $("#memberId").text();
    let page = pageNum

    const param = {
        mb_id: memberId,
        page: page,
    }

    $.ajax({
        url: '/src/controller/member/select_access.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify(param),
        success: function (data) {
            const resultCode = data.resultCode;
            if (resultCode == 10) {
                let accessList = data.value.list;
                let accessTotalCount = data.value.count[0].cnt;
                let html;
                accessList.forEach(function (access) {
                    if($("body").attr('data-device')=='pc') {
                        let resultMessage = "";
                        switch (access['ac_result']) {
                            case "10" :
                                resultMessage = lang('성공', 'Success', '成功','成功')
                                break;
                            case "11" :
                                resultMessage = lang('성공 - OTP 인증', 'Success - OTP Authentication', '成功 - OTP認証','成功 - OTP认证')
                                break;
                            case "12" :
                                resultMessage = lang('성공 - 이메일 인증', 'Success - Email Authentication', '成功 - EEメール認証','成功 - 电子邮箱认证')
                                break;
                            case "13" :
                                resultMessage = lang('성공 - 비밀번호 변경', 'Success - Change Password', '成功 - パスワード変更','成功 - 密码变更')
                                break;
                            case "20" :
                                resultMessage = lang('실패', 'Fail', '失敗','失败')
                                break;
                            case "21" :
                                resultMessage = lang('실패 - OTP 인증', 'Fail - OTP Authentication', '失敗 - OTP認証','失败 - OTP认证')
                                break;
                            case "22" :
                                resultMessage = lang('실패 - 아이디 또는 비밀번호 오류', 'Fail - ID or password error', '失敗 -' +
                                    ' IDまたはパスワードのエラー','失败 - 用户名或密码错误')
                                break;
                            case "23" :
                                resultMessage = lang('실패 - 이메일 인증', 'Fail - Email Authentication', '失敗 - Eメール認証','失败 - 电子邮箱认证')
                                break;
                            case "24" :
                                resultMessage = lang('실패 - 이메일 인증 코드 만료', 'Fail - Email Authentication Code Expired', '失敗 - Eメール認証コード満了','失败 - 电子邮件认证代码到期')
                                break;
                            case "25" :
                                resultMessage = lang('실패 - 비밀번호 변경', 'Fail - Change Password', '失敗 - パスワード変更','失败 - 密码变更')
                                break;
                        }
                        html += "<tr>"
                        html += "<td>" + formatDate(access['ac_datetime']) + "</td>";
                        html += "<td>" + access['ac_ip'] + "</td>";
                        html += "<td class='text-center'>" + access['ac_location'] + "</td>";
                        html += "<td class='text-center'>" + access['ac_device'] + "</td>";
                        html += "<td>" + resultMessage + "</td>";
                        html += "</tr>"
                    } else {
                        html += "<tr>"
                        html += "<td class='padding-left'>" + formatDate(access['ac_datetime']) + "</td>";
                        html += "<td>" + access['ac_ip'] + "</td>";
                        html += "<td>" + access['ac_location'] + "</td>";
                        html += "<td class='padding-right text-center'>" + access['ac_device'] + "</td>";
                        html += "</tr>"
                    }
                })
                $('.access-table-div .member-table tbody').html(html)
                let pageHtml = paging(accessTotalCount, page)
                $('.access-table-div .paging').html(pageHtml)
            } else {
                console.log('access 출력 ERR');
            }
        }, error: function (a, b, c) {
            console.error(c);
        }
    });
}

function paging(totalCnt, page, list, block) {
    list = (list == undefined) ? 10 : list;
    block = (block == undefined) ? 5 : block;

    let pageNum = Math.ceil(totalCnt / list); // 총 페이지
    let blockNum = Math.ceil(pageNum / block); // 총 블럭
    let nowBlock = Math.ceil(page / block); // 현재 블럭

    let startPage = (nowBlock * block) - 4;
    if (startPage <= 1)
        startPage = 1;
    let endPage = nowBlock * block;
    if (pageNum <= endPage)
        endPage = pageNum;

    let html = "";

    if (pageNum > 0) {
        html += "<ul>";
        if (nowBlock > 1) // 처음으로
            html += "<li class='page-arrow double-prev'><a href='javascript:getAccess(1)'></a></li>";

        if (nowBlock > 1 && page > 1) { // 이 전 블럭으로
            let prevPageNum = (nowBlock - 1) <= 1 ? 1 : block * (nowBlock - 2) + 1
            html += "<li class='page-arrow prev'><a href='javascript:getAccess(" + prevPageNum + ")'></a></li>";
        }
        for (let i = startPage; i <= endPage; i++) {
            html += "<li" + (i == page ? ' class="active"' : '') + "><a href='javascript:getAccess(" + i + ")'>" + i + "</a></li>";
        }
        if (pageNum != page && nowBlock < blockNum) { // 다음 블럭으로
            let nextPageNum = nowBlock + 1 == 1 ? 1 : (nowBlock * block) + 1
            html += "<li class='page-arrow next'><a href='javascript:getAccess(" + nextPageNum + ")'></a></li>";
        }
        if (nowBlock < blockNum) // 끝으로
            html += "<li class='page-arrow double-next'><a href='javascript:getAccess(" + pageNum + ")'></a></li>";
        html += "</ul>";
    }
    return html;
}

function formatDate(time) {
    time = new Date(time * 1000)
    let date = time.getDate();
    let month = time.getMonth();
    month = month + 1;
    let year = time.getFullYear();
    let min = time.getMinutes();
    let hour = time.getHours();
    if (month.toString().length == 1)
        month = '0' + month;
    if (date.toString().length == 1)
        date = '0' + date;
    if (hour.toString().length == 1)
        hour = '0' + hour;
    if (min.toString().length == 1)
        min = '0' + min;

    if($("body").attr('data-device')=='pc') {
        return year + "-" + month + "-" + date + " " + hour + ":" + min;
    } else {
        return year + "-" + month + "-" + date + "<br>" + hour + ":" + min;
    }        
}
