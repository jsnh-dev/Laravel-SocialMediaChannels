import './bootstrap';
import $ from './jquery';
import 'owl.carousel';
import ClipboardJS from "clipboard";

window.token = $('meta[name="csrf-token"]').attr('content');
window.appUrl = $('meta[name="app-url"]').attr('content');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': token
    },
});

Echo.channel('twitch.stream')
    .listen('.update', (data) => {
        if (data.stream) {
            $('.twitch-live-indicator').removeClass('d-none');
        } else {
            $('.twitch-live-indicator').addClass('d-none');
        }
    });

$(document).ready(function () {

    $(document).on('click', '.share-trigger', function() {
        openModalByScript('shareModal');
        $('#shareModal').data('route', $(this).data('route'));
    });

    document.getElementById('shareModal').addEventListener('show.bs.modal', function () {
        $(this).find('.modal-body-inner').html('');
        $('.modal.show:not(#shareModal)').addClass('z-index-1001');
    });

    document.getElementById('shareModal').addEventListener('shown.bs.modal', function () {
        ajaxLoaderCall(
            '#shareModal .modal-body-inner',
            'shareModalLoader',
            $(this).data('route'),
            initClipboardAsCallback,
            '#copyShareLink'
        );
    });

    document.getElementById('shareModal').addEventListener('hidden.bs.modal', function () {
        $('.modal.show:not(#shareModal)').removeClass('z-index-1001');
    });

});

window.initOwl = function initOwl() {
    $(".owl-carousel:not(.owl-loaded)").each(function() {
        initOwlElement($(this));
    });
}

window.initOwlElement = function initOwlElement(el) {
    el.trigger('destroy.owl.carousel');
    el.html(el.find('.owl-stage-outer').html()).removeClass('owl-loaded');
    el.owlCarousel({
        items: 1,
        center: true,
        nav: true,
        navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
        autoplay: el.data('autoplay') ?? false,
        loop: el.data('loop') ?? false,
        onResized: $(window).trigger('resize')
    });
}

window.initClipboard = function initClipboard(selector) {
    let clipboard = new ClipboardJS(selector);

    clipboard.on('success', function(e) {
        $(selector).parent().find('.feedback.success').removeClass('d-none');
        setTimeout(function () {
            $(selector).parent().find('.feedback.success').addClass('d-none');
        }, 5000);
        e.clearSelection();
    });

    clipboard.on('error', function() {
        $(selector).parent().find('.feedback.error').removeClass('d-none');
        setTimeout(function () {
            $(selector).parent().find('.feedback.error').addClass('d-none');
        }, 5000);
    });
}

window.initClipboardAsCallback = function initClipboardAsCallback(response, wrapperSelector, loaderId, url, selector) {
    initClipboard(selector);
}

window.openModalByScript = function openModalByScript(id) {
    let myModal = new bootstrap.Modal(document.getElementById(id), {});
    myModal.show();
}

window.ajaxLoaderInfinityUrls = {};
window.ajaxLoaderInfinityTriggered = {};

window.infinityLoaderElement = function infinityLoaderElement(id) {
    return $(
        '<div id="' + id + '" class="infinity-loader">' +
        '   <i class="fa-solid fa-spinner fa-spin fa-2x"></i>' +
        '</div>'
    );
}

window.infinityLoaderInit = function infinityLoaderInit(scroller, wrapper, adding, loader, callback, callbackParams) {

    $(scroller).unbind('scroll');

    $(scroller).scroll(function() {

        if ($(scroller).scrollTop() + $(scroller).height() + 200 >= $(wrapper).height() + adding) {

            infinityLoaderCall(scroller, wrapper, loader, callback, callbackParams);
        }
    });

    $(scroller).scroll();
}

window.infinityLoaderCall = function infinityLoaderCall(scroller, wrapper, loader, callback, callbackParams) {

    if (!ajaxLoaderInfinityTriggered[loader]) {
        ajaxLoaderInfinityTriggered[loader] = true;

        ajaxLoaderCall(
            wrapper,
            loader,
            ajaxLoaderInfinityUrls[loader],
            callback ?? infinityLoaderPaginate,
            callbackParams ?? scroller
        );
    }
}

window.infinityLoaderPaginate = function infinityLoaderPaginate(response, wrapper, loader, url, scroller) {

    if (Object.hasOwn(response, 'data')) {
        if (Object.hasOwn(response.data, 'current_page')) {
            if (response.data.current_page < response.data.last_page) {

                ajaxLoaderInfinityUrls[loader] = response.data.next_page_url;
                ajaxLoaderInfinityTriggered[loader] = false;

                if (scroller) {
                    $(scroller).scroll();
                }
            }
        }
    }
}

window.ajaxLoaderCall = function ajaxLoaderCall(wrapperSelector, loaderId, url, callback, callbackParams) {

    $(wrapperSelector).append(infinityLoaderElement(loaderId));

    $.ajax({
        method: 'get',
        url: url,
        success: function(response) {

            $('#' + loaderId).remove();

            $(wrapperSelector).append(response.view);

            setTimeout(() => {
                $(window).trigger('resize');
            }, '200');

            if (callback) {
                callback(response, wrapperSelector, loaderId, url, callbackParams);
            }
        }
    });
}

window.elementHtml = function elementHtml(el) {
    return $('<div />').append(el.eq(0).clone()).html();
};