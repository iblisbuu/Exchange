$(function () {
    let supportInto = (getParameter('type') != null && getParameter('type') != '') ? getParameter('type') : 'all';
    // 탭
    $('.support-lnb > a').removeClass('active');
    $('.' + supportInto + '-tab').addClass('active');
});