import $ from './jquery';

$(document).ready(function () {
    loadData();
});

function loadData() {
    loadDataPart('profile');
}

function loadDataPart(part, callback) {
    ajaxLoaderCall(
        '.' + part,
        part + 'Loader',
        appUrl + '/x/' + part,
        callback
    );
}