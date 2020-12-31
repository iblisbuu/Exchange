let recentWordList = [];

function recentWordSearch(obj){
    $('#searchValue').val($(obj).children().next().text())
}

function loadRecentWord() {
    const loadedRecentWord = getCookie('searchWord');
    if (loadedRecentWord != null) {
       const parsedRecentWord = JSON.parse(loadedRecentWord);
       recentWordList = JSON.parse(loadedRecentWord);
        if(parsedRecentWord.length > 20) {
            parsedRecentWord.shift()
        }
        parsedRecentWord.forEach(function (recentWord) {
            $('.recent-word-list').prepend('<div class="recent-search" onclick="recentWordSearch(this)"><span class="recent-search-ico"><div></div><div></div></span><span class="search-word">' + recentWord.text + 
            '</span></div></div>')
        });
    }
}