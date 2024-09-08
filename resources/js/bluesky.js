import $ from './jquery';

$(document).ready(function () {

    loadData();

});

function initPosts() {
    ajaxLoaderInfinityTriggered['postsLoader'] = false;
    ajaxLoaderInfinityUrls['postsLoader'] = appUrl + '/bluesky/posts';

    infinityLoaderInit(
        'main',
        '.posts',
        0,
        'postsLoader'
    );
}

function loadData() {
    loadDataPart('profile', initPosts);
}

function loadDataPart(part, callback) {
    ajaxLoaderCall(
        '.' + part,
        part + 'Loader',
        appUrl + '/bluesky/' + part,
        callback
    );
}