import './bootstrap';
import $ from './jquery';
import 'owl.carousel';
import ClipboardJS from "clipboard";

window.appUrl = $('meta[name="app-url"]').attr('content');

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

window.infinityLoaderElement = function infinityLoaderElement(id) {
    return $(
        '<div id="' + id + '" class="infinity-loader">' +
        '   <i class="fa-solid fa-spinner fa-spin fa-2x"></i>' +
        '</div>'
    );
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