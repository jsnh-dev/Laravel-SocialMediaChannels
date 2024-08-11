import $ from './jquery';
import './story';

$(document).ready(function () {

    if (!$('#cookieDisclaimerModal').length) {
        loadData();
    }

});

function initTabs() {

    let activeTab = $('button[data-bs-toggle="tab"].active');

    if (activeTab.length) {
        $('button[data-bs-toggle="tab"]').each(function (index, el) {
            ajaxLoaderInfinityUrls[$(el).data('loader')] = $(el).data('route');
            el.addEventListener('shown.bs.tab', event => {
                window.history.pushState("", "", appUrl + '/instagram?tab=' + $(el).data('name'));
                infinityLoaderInit(
                    'main',
                    $(el).data('wrapper'),
                    $($(el).data('wrapper')).offset().top,
                    $(el).data('loader')
                );
                // $(window).trigger('resize');
                window.dispatchEvent(new Event('resize'));
            });
        });

        window.history.pushState("", "", appUrl + '/instagram?tab=' + activeTab.data('name'));
        infinityLoaderInit(
            'main',
            activeTab.data('wrapper'),
            $(activeTab.data('wrapper')).offset().top,
            activeTab.data('loader')
        );

        setTimeout(() => {
            $('.nav-tabs.d-none').removeClass('d-none');
        }, '200');
    }
}

function loadData() {
    if ($('.container.instagram').length) {

        if ($('.profile').length) {
            loadDataPart('profile', function () {
                $('.profile-picture').on("load", function () {
                    $(this).parent().removeClass('opacity-0');
                    initStoryModalEvents();
                });
            });
        }

        initTabs();
    }
}

function loadDataPart(part, callback) {
    ajaxLoaderCall(
        '.' + part,
        part + 'Loader',
        appUrl + '/instagram/' + part,
        callback
    );
}