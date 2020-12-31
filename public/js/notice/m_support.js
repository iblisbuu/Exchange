$(function () {
    let supportInto = (getParameter('type') != null && getParameter('type') != '') ? getParameter('type') : 'all';
    // 탭
    $('.support-lnb>li').removeClass('active');
    $('.' + supportInto + '-tab>a').addClass('active');
});