<?php
// API 사용
function getApi($url, $data)
{
    $url = ""; // API 주소 :: http://api.rbto.io{$url}
    $ch = curl_init();// curl 리소스를 초기화
    curl_setopt($ch, CURLOPT_URL, $url); // url을 설정
    // 헤더는 제외하고 content 만 받음
    curl_setopt($ch, CURLOPT_HEADER, 0); // 응답 값을 브라우저에 표시하지 말고 값을 리턴
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $data = curl_exec($ch);
    curl_close($ch);// 리소스 해제를 위해 세션 연결 닫음

    $data = json_decode($data, true);// JSON 데이터를 배열로 변환

    return $data;
}

// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    $cookie = md5($cookie_name);
    if (array_key_exists($cookie, $_COOKIE))
        return base64_decode($_COOKIE[$cookie]);
    else
        return '';
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
    setcookie(md5($cookie_name), base64_encode($value), time() + $expire, '/', '');
}

function round_down($num, $d = 0)
{
    if ($d == 0) {
        $explode = explode('.', $num);
        return str_replace(',', '', $explode[0]);
    } else {
        $explode = explode('.', $num);
        if (strpos($explode[0], 'NAN') !== false || strpos($explode[0], 'INF') !== false || $num == 0)
            $explode[0] = 0;

        if (strlen(@$explode[1]) >= $d)
            $num = str_replace(',', '', $explode[0]) . '.' . substr(@$explode[1], 0, $d);
        else {
            $num = str_replace(',', '', $explode[0]) . '.' . @$explode[1];
            for ($i = 0; $i < $d - strlen(@$explode[1]); $i++)
                $num .= '0';
        }

        unset($explode);

        return $num;
    }
}

function round_down_int($num, $d = 0)
{
    if ($d == 0)
        return (int)$num;
    else {
        $explode = explode('.', $num);
        $num = $explode[0] . '.' . substr(isset($explode[1]), 0, $d);

        unset($explode);

        return (double)$num;
    }
}

function round_down_fix($num, $d = 0)
{
    $explode = explode('.', (string) $num);

    if ($d == 0)
        return ($explode[0]);
    else {
        $double = '';
        $explode[1] = str_split($explode[1]);

        for($i = ($d <= count($explode[1]) - 1 ? $d : count($explode[1]) - 1); $i>=0; $i--){
            if((int) $explode[1][$i] == 0 && $double == '')
                continue;

            $double = $explode[1][$i] . $double;
        }

        $num = ((float) $explode[0]) . ($double != '' ? '.' . $double : '');

        unset($explode);

        return $num;
    }
}

function round_down_format($num, $d = 0)
{
    $explode = explode('.', (string) $num);

    if ($d == 0)
        return number_format($explode[0]);
    else {
        $double = '';
        $explode[1] = str_split($explode[1]);

        for($i = ($d <= count($explode[1]) - 1 ? $d : count($explode[1]) - 1); $i>=0; $i--){
            if((int) $explode[1][$i] == 0 && $double == '')
                continue;

            $double = $explode[1][$i] . $double;
        }

        $num = number_format((float) $explode[0]) . ($double != '' ? '.' . $double : '');

        unset($explode);

        return $num;
    }
}

function round_down_format_fix($num, $d = 0)
{
    if ($d == 0) {
        $explode = explode('.', $num);
        return number_format($explode[0]);
    } else {
        $explode = explode('.', $num);
        $minus = strpos($explode[0], '-') !== false ? '-' : '';

        $explode[0] = str_replace('-', '', $explode[0]);

        if (strlen(@$explode[1]) >= $d)
            $num = number_format((float)$explode[0]) . '.' . substr(@$explode[1], 0, $d);
        else {
            $num = number_format((float)$explode[0]) . '.' . @$explode[1];
            for ($i = 0; $i < $d - strlen(@$explode[1]); $i++)
                $num .= '0';
        }

        unset($explode);

        return ($num != 0 ? $minus : '').$num;
    }
}

function get_microtime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function sendMail($to, $title, $content)
{
    $mail = new PHPMailer;
    $mail->SMTPSecure = 'ssl';

    $mail->isSMTP();

    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = RTC_SMTP;
    $mail->Port = RTC_SMTP_PORT;
    $mail->SMTPAuth = true;

    $mail->Username = RTC_SMTP_USER; // 계정
    $mail->Password = RTC_SMTP_PASSWORD; // 비밀번호

    $mail->setFrom(RTC_SMTP_USER, ''); // 보내는 사람 :: RBTO Wallet
    $mail->addAddress($to, 'User'); // 받는사람
    $mail->CharSet = 'UTF-8';

    $mail->Subject = $title;
    $mail->msgHTML($content); // 내용

    if (!$mail->send())
        return 0;
    else
        return 1;
}

function move($url)
{
    echo '<script>';
    echo "location.href='{$url}'";
    echo '</script>';
}

function alert($msg, $url = '')
{
    echo(
        '<script>' .
        'alert("' . $msg . '");' .
        ($url != '' ? 'location.href = "' . $url . '";' : 'history.back(-1);') .
        '</script>'
    );
}

function not_login()
{
    alert(lang('로그인 후 이용해주세요.', 'Please login and use it.', 'ログインしてからご利用ください。', '请登录后使用。'), '/member/login?url=' . urlencode($_SERVER['REQUEST_URI']));
}

function html_end()
{
    global $html_process;

    return $html_process->run();
}

function add_stylesheet($stylesheet, $order = 0)
{
    global $html_process;

    if (trim($stylesheet))
        $html_process->merge_stylesheet($stylesheet, $order);
}

function add_javascript($javascript, $order = 0)
{
    global $html_process;

    if (trim($javascript))
        $html_process->merge_javascript($javascript, $order);
}

class html_process
{
    protected $css = array();
    protected $js = array();

    function merge_stylesheet($stylesheet, $order)
    {
        $links = $this->css;
        $is_merge = true;

        foreach ($links as $link) {
            if ($link[1] == $stylesheet) {
                $is_merge = false;
                break;
            }
        }

        if ($is_merge)
            $this->css[] = array($order, $stylesheet);
    }

    function merge_javascript($javascript, $order)
    {
        $scripts = $this->js;
        $is_merge = true;

        foreach ($scripts as $script) {
            if ($script[1] == $javascript) {
                $is_merge = false;
                break;
            }
        }

        if ($is_merge)
            $this->js[] = array($order, $javascript);
    }

    function run()
    {
        $buffer = ob_get_contents();
        ob_end_clean();

        $stylesheet = '';
        $links = $this->css;

        if (!empty($links)) {
            foreach ($links as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $style[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $links);

            $links = run_replace('html_process_css_files', $links);

            foreach ($links as $link) {
                if (!trim($link[1]))
                    continue;

                $link[1] = preg_replace('#\.css([\'\"]?>)$#i', '.css?ver=' . GS_CSS_VER . '$1', $link[1]);

                $stylesheet .= PHP_EOL . $link[1];
            }
        }

        $javascript = '';
        $scripts = $this->js;
        $php_eol = '';

        unset($order);
        unset($index);

        if (!empty($scripts)) {
            foreach ($scripts as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $scripts);

            $scripts = run_replace('html_process_script_files', $scripts);

            foreach ($scripts as $js) {
                if (!trim($js[1]))
                    continue;

                $js[1] = preg_replace('#\.js([\'\"]?>)#', '.js?ver=' . GS_JS_VER . '$1', $js[1]);

                $javascript .= $php_eol . $js[1];
                $php_eol = PHP_EOL;
            }
        }

        $buffer = preg_replace('#(</title>[^<]*<link[^>]+>)#', "$1$stylesheet", $buffer);

        $nl = '';
        if ($javascript)
            $nl = "\n";
        $buffer = preg_replace('#(</head>[^<]*<body[^>]*>)#', "$javascript{$nl}$1", $buffer);

        return $buffer;
    }
}

function dater($dater)
{
    $date = BA_SERVER_TIME - $dater;

    if ($date < 60) {
        $date = '방금';
    } elseif ($date >= 60 and $date < 3600) {
        $date = floor($date / 60) . '분 전';
    } elseif ($date >= 3600 and $date < 86400) {
        $date = floor($date / 3600) . '시간 전';
    } elseif ($date >= 86400) {
        if (date('Ymd') - date('Ymd', $dater) == 1) {
            $date = '어제 ' . str_replace(array('PM', 'AM'), array('오후', '오전'), date('A g:i', $dater));
        } else {
            $month = date('n', $dater);
            $date = $month . '월 ' . date('j', $dater) . '일';
        }
    }

    return $date;
}

function objectToArray($d)
{
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

// json 형식 가져오기
function getJsonData($METHOD = '')
{
    if ($METHOD != '' && $METHOD != $_SERVER['REQUEST_METHOD']) {
        $api = new API();

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($api->callError(55));

        exit();
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $_GET = json_decode(file_get_contents('php://input'), true);
        $_GET = array_map_deep('stripslashes', $_GET);

        @extract($_GET);
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $_POST = array_map_deep('stripslashes', $_POST);

        @extract($_POST);
    } else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        global $_PUT;

        $_PUT = json_decode(file_get_contents('php://input'), true);
        $_PUT = array_map_deep('stripslashes', $_PUT);

        @extract($_PUT);
    } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        global $_DELETE;

        $_DELETE = json_decode(file_get_contents('php://input'), true);
        $_DELETE = array_map_deep('stripslashes', $_DELETE);

        @extract($_DELETE);
    } else {
        $api = new API();

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($api->callError(55));

        exit();
    }
}

// 입금 지갑 생성
function newWallet($coin, $label = 0)
{
    $label = 't' . $label; // 테스트중일때 지갑 라벨 앞에 t를 붙이도록함

    if($_SERVER['SERVER_NAME'] != '15.164.87.167' && $_SERVER['SERVER_NAME'] != 'genesis-ex.com') {
        // 테스트 서버
        if ($coin == 'btc')
            $url = 'http://15.164.152.210/btc_new_wallet.php?label=';
        else if ($coin == 'eth')
            $url = 'http://15.165.218.132/eth_new_wallet.php?label=';
        else if ($coin == 'fvc')
            $url = 'http://15.165.218.132/fvc_new_wallet.php?label=';
        else if ($coin == 'usdt')
            $url = 'http://15.165.218.132/usdt_new_wallet.php?label=';
        else if ($coin == 'usdt_btc')
            $url = 'http://3.35.66.63/usdt_new_wallet.php?label=';
        else
            return;
    } else {
        if ($coin == 'btc')
            $url = 'http://15.164.190.122/btc_new_wallet.php?label=';
        else if ($coin == 'eth')
            $url = 'http://3.35.102.91/eth_new_wallet.php?label=';
        else if ($coin == 'fvc')
            $url = 'http://3.35.102.91/fvc_new_wallet.php?label=';
        else if ($coin == 'usdt')
            $url = 'http://3.35.102.91/usdt_new_wallet.php?label=';
        else if ($coin == 'usdt_btc')
            $url = 'http://3.35.66.63/usdt_new_wallet.php?label=';
        else
            return;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url.$label);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($output, true);

    return $data;
}

// 서로 다른 가격의 비율 구하기
function priceToCoinRate($fromPrice, $fromCount, $toPrice, $toCount)
{
    # fromPrice = 사용자 주문 가격
    # fromCount = 사용자 주문 수량
    # toPrice = 등록되어있는 주문 가격
    # toCount = 등록되어있는 주문 수량

    if ($fromPrice == $toPrice) {
        // 가격이 같을때
        $result = round_down(($fromCount - $toCount), 4);
    } else {
        // 가격이 다를때
        $rateCoinCount = round_down($fromPrice / $toPrice * $fromCount, 4);

        $result = round_down($toCount - $rateCoinCount, 4);
    }

    return (double)$result;
}

function countToCoinRate($fromPrice, $toPrice, $nowCount)
{
    $rating = round_down($fromPrice / $toPrice, 2);
    $result = round_down($nowCount * $rating, 4);

    return (double)$result;
}

function paging($list_cnt, $page, $list = 10, $block = 5)
{
    $page_num = ceil($list_cnt / $list); // 총 페이지
    $block_num = ceil($page_num / $block); // 총 블럭
    $now_block = ceil($page / $block); // 현재 블럭

    $start_page = ($now_block * $block) - ($block - 1);

    if ($start_page <= 1)
        $start_page = 1;

    $end_page = $now_block * $block;
    if ($page_num < $end_page)
        $end_page = $page_num;

    $page_url = strpos($_SERVER['REQUEST_URI'], '?');
    $request_url = ($page_url) ? substr($_SERVER['REQUEST_URI'], 0, $page_url) : $_SERVER['REQUEST_URI'];
    unset($_GET['page']);
    $parameter = implode('&', array_map(
        function ($value, $key) {
            return sprintf("%s=%s", $key, $value);
        },
        $_GET,
        array_keys($_GET)
    ));
    if ($parameter) {
        $request_url .= '?' . $parameter;
    }
    $connect = ($parameter != '') ? '&' : '?';
    $url = $request_url . $connect;

    if ($page_num > 0) {
        echo "<div class='paging'><ul>";

        if ($now_block > 1) // 처음으로
            echo "<li class='page-arrow double-prev'><a href='{$url}page=1'></a></li>";

        if ($now_block > 1 && $page > 1) { // 이 전 블럭으로
            $prev_page = floor(($page - $block) / 5);
            $prev_page = 1 + ($prev_page * $block);
            echo "<li class='page-arrow prev'><a href='{$url}page={$prev_page}'></a></li>";
        }
        for ($i = $start_page; $i <= $end_page; $i++) {
            echo "<li" . ($i == $page ? ' class="active"' : '') . "><a href='{$url}page={$i}'>{$i}</a></li>";
        }

        if (($page_num != $page || $now_block < $block_num) && ($page + $block) < $page_num) { // 다음 블럭으로
            $next_page = floor(($page + $block) / 5);
            $next_page = 1 + ($next_page * $block);
            echo "<li class='page-arrow next'><a href='{$url}page={$next_page}'></a></li>";
        }
        if ($now_block < $block_num) // 끝으로
            echo "<li class='page-arrow double-next'><a href='{$url}page={$page_num}'></a></li>";

        echo "</ul></div>";
    }
}

function settleTrade($tr_no)
{
    $db = new db();

    $settlePrice = 0;
    $query = "SELECT td_amount, td_price FROM _orders WHERE td_no = '{$tr_no}'";
    $settle = $db->fetchAll($query);
    foreach ($settle as $set) {
        $settlePrice += $set->td_amount * $set->td_price;
    }

    return $settlePrice;
}

function pointLogInsert($mb_id, $symbol, $point, $type, $table = '', $num = 0, $time = '')
{
    $db = new db();

    $time = $time == '' ? time() : $time;

    $query = "INSERT INTO _pointLog SET mb_id = '{$mb_id}', pl_symbol = '{$symbol}', pl_point = '{$point}', pl_type = '{$type}', pl_table = '{$table}', pl_id = '{$num}', pl_datetime = '{$time}'";
    return $db->execute($query);
}

function decimalDrainage($value, $decimal){
    $result = $value - $decimal;

    if($result == 0)
        return 0;
    else if($result < 0)
        return -1;
    else
        decimalDrainage($result, $decimal);
}
?>