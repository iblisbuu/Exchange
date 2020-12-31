<?php
add_stylesheet('<link rel="stylesheet" href="' . ROOT . 'public/css/exchange/m_coinlist.css">');
add_javascript('<script type="text/javascript"src="' . ROOT . 'public/js/exchange/m_main.js"></script>');

?>
<div id="exchange">
    <div class="ex01">
        <?php include_once VIEW_ROOT . '/exchange/coinList.php';?>
    </div>
</div>