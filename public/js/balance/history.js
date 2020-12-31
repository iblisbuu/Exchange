$(function () {
    // $('#refreshBtn').click(function () {
    //     let contractOption = $('.select-contract input').attr('data-select')
    //     let coinOption = $('.select-coin input').attr('data-select')
    //     let dateOption = $('.select-date input').attr('data-select')
    //
    //     let menu = (getParameter('menu') != null) ? getParameter('menu') : 'asset';
    //     let url = $(location).attr('pathname') + '?menu=' + menu
    //     if (contractOption) {
    //         url += '&contract=' + contractOption
    //     }
    //     if (coinOption) {
    //         url += '&coin=' + coinOption
    //     }
    //     if (dateOption) {
    //         url += '&date=' + dateOption
    //     }
    //     location.href = url
    // })
});

function openStatusDesc() {
    if ($('.popup-box.balance.status').length > 0) {
        closePopup();
    } else {
        const language = $("body").attr('data-lang');
        const desc = {
            'ko': {
                'complete': "<em>완료</em><br/>" +
                    "거래가 완료되어 자산에 반영 완료된 상태",
                'fail': "<em>실패</em><br/>" +
                    "거래를 접수했지만 해당 거래 건이 종료되었거나, 거래 잔여수량이 접수요청</br>" +
                    "수량보다 적을 경우 거래 실패로 처리",
                'ing': "<em>진행 중</em><br/>" +
                    "거래를 접수했지만 간혹 지연이 발생되는 상태로, 일정 시간이 지난 후 자동으로<br/>" +
                    "완료 혹은 실패로 처리"
            },
            'ja': {
                'complete': "<em>完了</em><br/>" +
                    "取引が完了されて資産に反映完了した状態",
                'fail': "<em>失敗</em><br/>" +
                    "取引を受理したが、その取引件が終了しているか</br>" +
                    "取引残りの数量が受信済みの数量よりも少ない場合取引の失敗で処理",
                'ing': "<em>進行中</em><br/>" +
                    "取引を受理したが、時折、遅延が発生する状態で、時間が経過した後に自動的に終了あるいは失敗として処理"
            },
            'en': {
                'complete': "<em>Completion</em><br/>" +
                    "The status of transactions completed and reflected in assets",
                'fail': "<em>Failure</em><br/>" +
                    "Received transaction but the transaction has been terminated or remaining transaction quantity is requested</br>" +
                    "Deal failure if less than quantity",
                'ing': "<em>Ongoing</em><br/>" +
                    "Receiving transactions, but with occasional delays, automatic completion or failure after a certain period of time"
            },
            'ch': {
                'complete': "<em>完成</em><br/>" +
                    "已成交",
                'fail': "<em>失败</em><br/>" +
                    "已接受交易,但该交易事项已终止,或交易剩余数量的申请</br>" +
                    "数量少于数量时,以交易失败处理",
                'ing': "<em>进行中</em><br/>" +
                    "虽然接受了交易,但是偶尔会发生延迟的状态,过一段时间后自动<br/>" +
                    "完成或失败处理"
            }
        }


        let html = '<div class="popup-box balance status">';
        html += '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>';
        html += '<div class="popup-content">';
        html += '<p>' + desc[language].complete + '</p>';
        html += '<p>' + desc[language].fail + '</p>';
        html += '<p>' + desc[language].ing + '</p>';
        html += '</div>';
        html += '</div>';
        openCustomPopup(html, '.statusDesc')
    }
}

function MobileOpenStatusDesc() {
    if ($('.popup-box.balance.status').length > 0) {
        closePopup();
    } else {
        const language = $("body").attr('data-lang');
        const desc = {
            'ko': {
                'complete': "<em>완료</em><br/>" +
                    "거래가 완료되어 자산에 반영 완료된 상태",
                'fail': "<em>실패</em><br/>" +
                    "거래를 접수했지만 해당 거래 건이 종료되었거나, 거래 잔여수량이 접수요청</br>" +
                    "수량보다 적을 경우 거래 실패로 처리",
                'ing': "<em>진행 중</em><br/>" +
                    "거래를 접수했지만 간혹 지연이 발생되는 상태로, 일정 시간이 지난 후 자동으로<br/>" +
                    "완료 혹은 실패로 처리"
            },
            'ja': {
                'complete': "<em>完了</em><br/>" +
                    "取引が完了されて資産に反映完了した状態",
                'fail': "<em>失敗</em><br/>" +
                    "取引を受理したが、その取引件が終了しているか</br>" +
                    "取引残りの数量が受信済みの数量よりも少ない場合取引の失敗で処理",
                'ing': "<em>進行中</em><br/>" +
                    "取引を受理したが、時折、遅延が発生する状態で、時間が経過した後に自動的に終了あるいは失敗として処理"
            },
            'en': {
                'complete': "<em>Completion</em><br/>" +
                    "The status of transactions completed and reflected in assets",
                'fail': "<em>Failure</em><br/>" +
                    "Received transaction but the transaction has been terminated or remaining transaction quantity is requested</br>" +
                    "Deal failure if less than quantity",
                'ing': "<em>Ongoing</em><br/>" +
                    "Receiving transactions, but with occasional delays, automatic completion or failure after a certain period of time"
            },
            'ch': {
                'complete': "<em>完成</em><br/>" +
                    "已成交",
                'fail': "<em>失败</em><br/>" +
                    "已接受交易,但该交易事项已终止,或交易剩余数量的申请</br>" +
                    "数量少于数量时,以交易失败处理",
                'ing': "<em>进行中</em><br/>" +
                    "虽然接受了交易,但是偶尔会发生延迟的状态,过一段时间后自动<br/>" +
                    "完成或失败处理"
            }
        }


        let html = '<div class="popup-box balance status">';
        html += '<span class="closeBtn" onclick="closePopup()" style="display:block"><i class="xi-close-thin"></i></span>';
        html += '<div class="popup-content">';
        html += '<p>' + desc[language].complete + '</p>';
        html += '<p>' + desc[language].fail + '</p>';
        html += '<p>' + desc[language].ing + '</p>';
        html += '</div>';
        html += '</div>';
        html += '<div class="popup-bg"></div>';
        openCustomPopup(html, '.history-table')
    }
}

function openDepositStatusDesc() {
    if ($('.popup-box.balance.deposit').length > 0) {
        closePopup();
    } else {
        const language = $("body").attr('data-lang');
        const desc = {
            'ko': {
                'complete': "<em>완료</em><br/>" +
                    "-입금 : 입금내역이 확인 되어, 지갑에 반영된 상태.<br/>" +
                    "-출금 : 출금요청이 승인된 상태. 출금 요청내역이 고객정보와 일치하며,<br>" +
                    "보안상 특이사항이 없는 경우",
                'cancel': "<em>취소</em><br/>" +
                    "-입출금 요청이 보안 혹은 기타 이유로 취소 처리된 상태.",
                'ing': "<em>대기 중</em><br/>" +
                    "-입금 : 아직 입금내역이 확인되지 않아 입금 대기중인 상태.<br/>" +
                    "입금 확인 후 ‘완료’로 상태 변경됨."
            },
            'ja': {
                'complete': "<em>完了</em><br/>" +
                    "-入金：入金履歴が確認されて、ウォレットに反映された状態。<br/>" +
                    "-出金：出金要求が承認された状態。出金依頼内容が顧客情報と一致し、セキュリティ上の特異点が存在しない場合",
                'cancel': "<em>キャンセル</em><br/>" +
                    "-入出金リクエストがセキュリティやその他の理由でキャンセル処理された状態。",
                'ing': "<em>待機中</em><br/>" +
                    "-入金：まだ入金履歴が確認されていない入金待ち状態。<br/>" +
                    "入金確認後、「完了」に状態変更。"
            },
            'en': {
                'complete': "<em>Completion</em><br/>" +
                    "-Deposit: The deposit details have been confirmed and reflected in the wallet.<br/>" +
                    "-withdrawal: The withdrawal request has been approved. If the withdrawal request details match the customer information and there is nothing special about the security.",
                'cancel': "<em>Failure</em><br/>" +
                    "-The state in which the deposit and withdrawal request has been canceled for security or other reasons.",
                'ing': "<em>Standby</em><br/>" +
                    "-Deposit: The deposit status has not been confirmed yet and is waiting for deposit.<br/>" +
                    "Status changed to 'Complete' after confirmation of deposit."
            },
            'ch': {
                'complete': "<em>完成</em><br/>" +
                    "-汇款 : 已确认汇款明细,已反映在钱包里。<br/>" +
                    "-出纳 : 出纳申请已获批准。 要求付款的明细与顾客信息一致,<br>" +
                    "保安上无特殊事项时",
                'cancel': "<em>取消</em><br/>" +
                    "-由于保安或其他原因,已经取消了汇款申请。",
                'ing': "<em>待机</em><br/>" +
                    "-汇款:尚未确认汇款明细,处于等待汇款的状态。<br/>" +
                    "确认汇款后变更为‘完成’。"
            },
        }


        let html = '<div class="popup-box balance deposit">';
        html += '<span class="closeBtn" onclick="closePopup()"><i class="xi-close-thin"></i></span>';
        html += '<div class="popup-content">';
        html += '<p>' + desc[language].complete + '</p>';
        html += '<p>' + desc[language].cancel + '</p>';
        html += '<p>' + desc[language].ing + '</p>';
        html += '</div>';
        html += '</div>';
        openCustomPopup(html, '.statusDesc')
    }
}

function MobileOpenDepositStatusDesc() {
    if ($('.popup-box.balance.deposit').length > 0) {
        closePopup();
    } else {
        const language = $("body").attr('data-lang');
        const desc = {
            'ko': {
                'complete': "<em>완료</em><br/>" +
                    "-입금 : 입금내역이 확인 되어, 지갑에 반영된 상태.<br/>" +
                    "-출금 : 출금요청이 승인된 상태. 출금 요청내역이 고객정보와 일치하며,<br>" +
                    "보안상 특이사항이 없는 경우",
                'cancel': "<em>취소</em><br/>" +
                    "-입출금 요청이 보안 혹은 기타 이유로 취소 처리된 상태.",
                'ing': "<em>대기 중</em><br/>" +
                    "-입금 : 아직 입금내역이 확인되지 않아 입금 대기중인 상태.<br/>" +
                    "입금 확인 후 ‘완료’로 상태 변경됨."
            },
            'ja': {
                'complete': "<em>完了</em><br/>" +
                    "-入金：入金履歴が確認されて、ウォレットに反映された状態。<br/>" +
                    "-出金：出金要求が承認された状態。出金依頼内容が顧客情報と一致し、セキュリティ上の特異点が存在しない場合",
                'cancel': "<em>キャンセル</em><br/>" +
                    "-入出金リクエストがセキュリティやその他の理由でキャンセル処理された状態。",
                'ing': "<em>待機中</em><br/>" +
                    "-入金：まだ入金履歴が確認されていない入金待ち状態。<br/>" +
                    "入金確認後、「完了」に状態変更。"
            },
            'en': {
                'complete': "<em>Completion</em><br/>" +
                    "-Deposit: The deposit details have been confirmed and reflected in the wallet.<br/>" +
                    "-withdrawal: The withdrawal request has been approved. If the withdrawal request details match the customer information and there is nothing special about the security.",
                'cancel': "<em>Failure</em><br/>" +
                    "-The state in which the deposit and withdrawal request has been canceled for security or other reasons.",
                'ing': "<em>Standby</em><br/>" +
                    "-Deposit: The deposit status has not been confirmed yet and is waiting for deposit.<br/>" +
                    "Status changed to 'Complete' after confirmation of deposit."
            },
            'ch': {
                'complete': "<em>完成</em><br/>" +
                    "-汇款 : 已确认汇款明细,已反映在钱包里。<br/>" +
                    "-出纳 : 出纳申请已获批准。 要求付款的明细与顾客信息一致,<br>" +
                    "保安上无特殊事项时",
                'cancel': "<em>取消</em><br/>" +
                    "-由于保安或其他原因,已经取消了汇款申请。",
                'ing': "<em>待机</em><br/>" +
                    "-汇款:尚未确认汇款明细,处于等待汇款的状态。<br/>" +
                    "确认汇款后变更为‘完成’。"
            },
        }


        let html = '<div class="popup-box balance deposit">';
        html += '<span class="closeBtn" onclick="closePopup()" style="display:block"><i class="xi-close-thin"></i></span>';
        html += '<div class="popup-content">';
        html += '<p>' + desc[language].complete + '</p>';
        html += '<p>' + desc[language].cancel + '</p>';
        html += '<p>' + desc[language].ing + '</p>';
        html += '</div>';
        html += '</div>';
        html += '<div class="popup-bg"></div>';
        openCustomPopup(html, '.history-table')
    }
}