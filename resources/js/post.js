import $ from './jquery';

$(document).ready(function () {

    $(document).on('click', '.post-playlist-item', function(e) {
        if(!e.ctrlKey) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            clickedPostModalTrigger = $(this);
            initPostModalTrigger($(this));
            initPost($(this));
        }
    });

    $(document).on('click', '[data-target="#postModal"]', function (e) {
        if(!e.ctrlKey) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            clickedPostModalTrigger = $(this);
            initPostModalTrigger($(this));
            $('#postModalTrigger').click();
        }
    });

    $(document).on('click', '[data-bs-target="#postModal"]', function (e) {
        // initPostModalTrigger($(this));
    });

    $(document).on('click', '.post-nav.post-next .post-nav-button', function () {
        var el = $('.post-wrapper[data-id="' + $('.post-details-wrapper:visible').data('id') + '"]:visible').nextAll('a.post-wrapper').first();

        if (el.length && el.hasClass('post-wrapper')) {
            initPostModalTrigger(el);
            initPost(el);
        } else {
            $('[data-bs-dismiss="modal"]:visible').click();
        }
    });

    $(document).on('click', '.post-nav.post-previous .post-nav-button', function () {
        var el = $('.post-wrapper[data-id="' + $('.post-details-wrapper:visible').data('id') + '"]:visible').prevAll('a.post-wrapper').last();

        if (el.length && el.hasClass('post-wrapper')) {
            initPostModalTrigger(el);
            initPost(el);
        } else {
            $('[data-bs-dismiss="modal"]:visible').click();
        }
    });

    $(document).on('click', '.post-details-comment-view-replies', function () {
        let parent = $(this).parents('.post-details-comment-replies');

        $(this).addClass('d-none');

        parent.find('.post-details-comment-hide-replies').removeClass('d-none');
        parent.find('.post-details-comment-replies-wrapper').removeClass('d-none');
        parent.find('.post-details-comment-replies-load-trigger').removeClass('d-none');

        parent.find('.post-details-comment-view-more-replies').addClass('d-none');

        let wrapper = '.post-details-comment-replies[data-id="' + parent.data('id') + '"] .post-details-comment-replies-wrapper';
        let loader = 'postCommentRepliesLoader' + parent.data('id');

        initPostCommentReplies(wrapper, loader, parent.data('route'));
    });

    $(document).on('click', '.post-details-comment-hide-replies', function () {
        let parent = $(this).parents('.post-details-comment-replies');

        parent.find('.post-details-comment-view-replies').removeClass('d-none');

        $(this).addClass('d-none');

        parent.find('.post-details-comment-replies-wrapper').addClass('d-none');
        parent.find('.post-details-comment-replies-load-trigger').addClass('d-none');
        parent.find('.post-details-comment-view-more-replies').addClass('d-none');

        let wrapper = '.post-details-comment-replies[data-id="' + parent.data('id') + '"] .post-details-comment-replies-wrapper';

        $(wrapper).html('');
    });

    $(document).on('click', '.post-details-comment-view-more-replies', function () {
        $(this).addClass('d-none');

        let parent = $(this).parents('.post-details-comment-replies');
        let wrapper = '.post-details-comment-replies[data-id="' + parent.data('id') + '"] .post-details-comment-replies-wrapper';
        let loader = 'postCommentRepliesLoader' + parent.data('id');

        ajaxLoaderCall(
            wrapper,
            loader,
            ajaxLoaderInfinityUrls[loader],
            initPostCommentRepliesCallback,
            false
        );
    });

    $(window).on('resize', function () {
        resizePostDetails();
    });

    initPostModalEvents();
});

window.clickedPostModalTrigger = 0;

window.initPostModalEvents = function initPostModalEvents() {
    if ($('#postModal').length) {

        if ($('.hidden-post-modal-trigger').length) {
            $('.hidden-post-modal-trigger').click();
        }

        document.getElementById('postModal').addEventListener('shown.bs.modal', function () {
            initPost(
                typeof clickedPostModalTrigger != "undefined"
                    ? clickedPostModalTrigger
                    :( $('[data-target="#postModal"][data-id="' + $(this).attr('data-id') + '"]:visible').length
                        ? $('[data-target="#postModal"][data-id="' + $(this).attr('data-id') + '"]:visible').first()
                        : $('[data-target="#postModal"][data-id="' + $(this).attr('data-id') + '"]').first() )
            );
        });

        document.getElementById('postModal').addEventListener('hide.bs.modal', function () {
            window.history.pushState("", "", $(this).data('close-route'));
        });

        document.getElementById('postModal').addEventListener('hidden.bs.modal', function () {
            let wrapperSelector = $(this).find('.modal-body-inner');
            $(wrapperSelector).html('');
        });
    }
}

window.initPostModalTrigger = function initPostModalTrigger(trigger) {
    let modalSelector = '#postModal';
    let wrapperSelector = modalSelector + ' .modal-body-inner';

    $(wrapperSelector).html('');

    $(modalSelector).removeClass('z-index-1001');
    $(modalSelector).data('id', trigger.data('id'));
    $(modalSelector).data('route', trigger.data('route'));
    $(modalSelector).data('close-route', trigger.data('close-route'));
    $(modalSelector).attr('data-id', trigger.data('id'));
    $(modalSelector).attr('data-route', trigger.data('route'));
    $(modalSelector).attr('data-close-route', trigger.data('close-route'));

    if (trigger.hasClass('youtube')) {
        $(modalSelector).find('.modal-dialog').addClass('max-height');
    } else {
        $(modalSelector).find('.modal-dialog').removeClass('max-height');
    }
}

window.initPost = function initPost(trigger) {
    let modalSelector = '#postModal';
    let wrapperSelector = modalSelector + ' .modal-body-inner';
    let loaderId = 'postModalLoader';

    ajaxLoaderInfinityTriggered[loaderId] = false;
    ajaxLoaderInfinityUrls[loaderId] = trigger.data('route');

    ajaxLoaderCall(
        wrapperSelector,
        loaderId,
        trigger.data('route'),
        initPostCallback,
        trigger
    );

    window.history.pushState("", "", trigger.data('route'));
}

window.initPostCallback = function initPostCallback(response, wrapperSelector, loaderId, url, trigger) {

    initPostNav(response, wrapperSelector, loaderId, url, trigger);
    initOwl();

    let next = $('.post-wrapper[data-id="' + trigger.data('id') + '"]:visible').nextAll('a.post-wrapper').first();

    if (!next.length) {
        infinityLoaderCall(
            'main',
            $('button[data-bs-toggle="tab"].active').length ? $('button[data-bs-toggle="tab"].active').data('wrapper') : '.posts',
            $('button[data-bs-toggle="tab"].active').length ? $('button[data-bs-toggle="tab"].active').data('loader') : 'postsLoader',
            initPostNavCallback,
            trigger
        );
    }

    initPostComments(response, wrapperSelector, loaderId, url, trigger);
}

window.initPostNav = function initPostNav(response, wrapperSelector, loaderSelector, url, trigger) {
    $('.post-details-title').html($('.post-details-title-hidden').html());

    let next = $('.post-wrapper[data-id="' + trigger.data('id') + '"]:visible').nextAll('a.post-wrapper').first();

    if (next.length && next.hasClass('post-wrapper')) {
        $('.post-nav.post-next').removeClass('d-none');
    } else {
        $('.post-nav.post-next').addClass('d-none');
    }

    let previous = $('.post-wrapper[data-id="' + trigger.data('id') + '"]:visible').prevAll('a.post-wrapper').last();

    if (previous.length && previous.hasClass('post-wrapper')) {
        $('.post-nav.post-previous').removeClass('d-none');
    } else {
        $('.post-nav.post-previous').addClass('d-none');
    }
}

window.initPostNavCallback = function initPostNavCall(response, wrapper, loader, url, trigger) {
    infinityLoaderPaginate(response, wrapper, loader, url, 'main');

    initPostNav(response, wrapper, loader, url, trigger);
}

window.initPostComments = function initPostComments(response, wrapperSelector, loaderId, url, trigger) {
    ajaxLoaderInfinityTriggered['postCommentsModalLoader'] = false;
    ajaxLoaderInfinityUrls['postCommentsModalLoader'] = trigger.data('route-comments');

    infinityLoaderInit(
        '#postModal .modal-body .post-details-comments',
        '#postModal .modal-body-inner .post-details-comments-inner',
        0,
        'postCommentsModalLoader'
    );
}

window.initPostCommentReplies = function initPostCommentReplies(wrapper, loader, url) {
    ajaxLoaderInfinityTriggered[loader] = false;
    ajaxLoaderInfinityUrls[loader] = url;

    ajaxLoaderCall(
        wrapper,
        loader,
        ajaxLoaderInfinityUrls[loader],
        initPostCommentRepliesCallback,
        false
    );
}

window.initPostCommentRepliesCallback = function initPostCommentRepliesCallback(response, wrapper, loader, url) {
    ajaxLoaderInfinityTriggered[loader] = true;

    infinityLoaderPaginate(response, wrapper, loader, url, false);

    if (!ajaxLoaderInfinityTriggered[loader]) {
        let parent = $(wrapper).parents('.post-details-comment-replies');

        parent.find('.post-details-comment-view-more-replies').removeClass('d-none');
    }
}

window.resizePostDetails = function resizePostDetails() {
    if ($('.post-details-image:visible').length) {
        $('.post-details-comments-wrapper').css('height', $('.post-details-image:visible').height() + 'px');
        $('.post-details-comments-wrapper').removeClass('opacity-0');
        $('.modal-dialog:visible').css('width', '');
        $('.modal-dialog:visible').css('width', 'calc(' + $('.post-details-image:visible').width() + 'px + ' + $('.post-details-comments-wrapper:visible').width() + 'px + 12rem + 2.5px)')
    }
}