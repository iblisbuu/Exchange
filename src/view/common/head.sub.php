<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no, viewport-fit=cover"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta property="og:image" content="https://new.genesis-ex.com/public/img/common/mobile/url_share.jpg">
    <meta property="og:image:secure_url" content="https://new.genesis-ex.com/public/img/common/mobile/url_share.jpg">
    <title>Genesis·EX</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>public/css/common/common.css?ver=<?= GS_JS_VER ?>"/>
    <link rel="icon" type="image/png" href="<?= ROOT ?>public/img/common/favicon.png">
    <link rel="apple-touch-icon" href="<?= ROOT ?>public/img/common/mobile/home-ios.png" />
    <link rel="shortcut icon" href="<?= ROOT ?>public/img/common/mobile/home-aos.png" />
    <script type="text/javascript" src="<?= ROOT ?>public/js/common/jquery-3.2.1.min.js?ver=<?= GS_JS_VER ?>"></script>
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
    <script type="text/javascript" src="<?= ROOT ?>public/js/common/jquery.animateNumber.min.js"></script>
    <script>
        <?php if($_SERVER['SERVER_NAME'] == '15.164.87.167' || $_SERVER['SERVER_NAME'] == 'genesis-ex.com'){?>
        const socket = io("https://socket.genesis-ex.com");
        <?php } else {?>
        const socket = io("https://testsocket.genesis-ex.com");
        <?php }?>
    </script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
    <script type="text/javascript" src="<?= ROOT ?>public/js/common/common.js?ver=<?= GS_JS_VER ?>"></script>
    <?php if ($device == 'mobile') { ?>
        <link rel="stylesheet" href="<?= ROOT ?>public/css/common/m_common.css?ver=<?= GS_JS_VER ?>"/>
    <?php } ?>
</head>
<body data-lang="<?= $country ?>" data-device="<?= $device ?>">