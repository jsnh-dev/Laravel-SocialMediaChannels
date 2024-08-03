import $ from './jquery';

$(document).ready(function () {

    loadData();

    $(document).on('click', '.post-details-caption.hidden', function () {
        if (!$('.post-details-comment-metrics:hover').length) {
            $(this).addClass('shown');
            $(this).removeClass('hidden');
        }
    });

    $(document).on('click', '.post-details-caption.shown .show-less', function () {
        $(this).parents('.post-details-caption.shown').addClass('hidden');
        $(this).parents('.post-details-caption.shown').find('.post-description').scrollTop(0);
        $(this).parents('.post-details-caption.shown').removeClass('shown');
    });

    $(window).on('resize', function () {
        $('.profile-banner').height($('.profile-banner').width() / 6.25);
    });
});


function initTabs() {
    let activeTab = $('button[data-bs-toggle="tab"].active');

    if (activeTab.length) {
        $('button[data-bs-toggle="tab"]').each(function (index, el) {
            ajaxLoaderInfinityUrls[$(el).data('loader')] = $(el).data('route');
            el.addEventListener('shown.bs.tab', event => {
                window.history.pushState("", "", appUrl + '/youtube?tab=' + $(el).data('name'));
                infinityLoaderInit(
                    'main',
                    $(el).data('wrapper'),
                    $($(el).data('wrapper')).offset().top,
                    $(el).data('loader')
                );
                $(window).trigger('resize');
            });
        });

        window.history.pushState("", "", appUrl + '/youtube?tab=' + activeTab.data('name'));
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
    if ($('.container.youtube').length) {

        if ($('.profile').length) {
            loadDataPart('profile');
        }

        initTabs();
    }
}

function loadDataPart(part, callback) {
    ajaxLoaderCall(
        '.' + part,
        part + 'Loader',
        appUrl + '/youtube/' + part,
        callback
    );
}