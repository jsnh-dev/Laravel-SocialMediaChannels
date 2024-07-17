import $ from 'jquery/dist/jquery';

$(document).ready(function () {
    loadData();
});

function loadData() {
    loadDataPart('teaser', initOwl);
}

function loadDataPart(part, callback) {
    ajaxLoaderCall(
        '.' + part,
        part + 'Loader',
        appUrl + '/' + part,
        callback
    );
}