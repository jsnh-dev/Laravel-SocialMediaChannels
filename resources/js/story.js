import $ from './jquery';

$(document).ready(function () {

    $(document).on('click', '.story-pause', function () {
        $('.story-wrapper:visible').addClass('paused');
        storiesIntervalPause();
        $(this).addClass('story-resume');
        $(this).removeClass('story-pause');
        $(this).find('i').addClass('fa-play');
        $(this).find('i').removeClass('fa-pause');
    });

    $(document).on('click', '.story-resume', function () {
        $('.story-wrapper:visible').removeClass('paused');
        storiesIntervalResume();
        $(this).addClass('story-pause');
        $(this).removeClass('story-resume');
        $(this).find('i').addClass('fa-pause');
        $(this).find('i').removeClass('fa-play');
    });

    $(document).on('click', '.story-wrapper', function () {
        if ($('.story-pause:visible').length) {
            $('.story-pause:visible').click();
        } else {
            $('.story-resume:visible').click();
        }
    });

    $(document).on('click', '.story-nav.story-next .story-nav-button', function () {
        $('.story-pause').click();
        remainingTime = 0;
        $('.story-resume').click();
    });

    $(document).on('click', '.story-nav.story-previous .story-nav-button', function () {
        $('.story-pause').click();
        remainingTime = 0;
        counter--;
        counter--;
        $('.story-resume').click();
    });

});

window.initStoryModalEvents = function initStoryModalEvents() {
    if ($('#storyModal').length) {
        document.getElementById('storyModal').addEventListener('show.bs.modal', function () {
            $('#storyModal .modal-body-inner').html('');
        });

        document.getElementById('storyModal').addEventListener('shown.bs.modal', function () {
            if ($(".owl-carousel.owl-loaded:visible").length) {
                $(".owl-carousel.owl-loaded:visible").first().trigger('stop.owl.autoplay');
            }
            ajaxLoaderCall(
                '#storyModal .modal-body-inner',
                'storyModalLoader',
                $(this).data('route'),
                initStories
            );
        });

        document.getElementById('storyModal').addEventListener('hidden.bs.modal', function () {
            if ($(".owl-carousel.owl-loaded:visible").length) {
                $(".owl-carousel.owl-loaded:visible").first().trigger('play.owl.autoplay');
            }
            closeStories();
        });
    }
}

var interval;
var intervalTime = 5000;
var remainingTime = intervalTime;
var counter = 0;
var lastIntervalStart;
var storyWrapper;

window.initStories = function initStories() {
    storyWrapper = $('.modal-body:visible .story-wrapper');
    storyWrapper.removeClass('active');
    storyWrapper.eq(0).addClass('active');

    remainingTime = intervalTime = 5000;
    counter = 0;
    $('.story-counter').html(counter+1);
    storiesIntervalStart();
}

window.closeStories = function closeStories() {
    $('.story-resume').click();
    clearInterval(interval);
    $('[data-bs-dismiss="modal"]:visible').click();
}

window.storiesIntervalStart = function storiesIntervalStart() {
    if ($('.story-counter').html() > 1) {
        $('.story-nav.story-previous').removeClass('d-none');
    } else {
        $('.story-nav.story-previous').addClass('d-none');
    }
    if ($('.story-counter').html() < $('.story-counter-max').html()) {
        $('.story-nav.story-next').removeClass('d-none');
    } else {
        $('.story-nav.story-next').addClass('d-none');
    }
    interval = setInterval(function() {
        counter++;
        storyWrapper.removeClass('active');
        if (storyWrapper.eq(counter).length) {
            $('.story-counter').html(counter+1);
            storyWrapper.eq(counter).addClass('active');
        } else {
            closeStories();
        }
        remainingTime = intervalTime = 5000;
        clearInterval(interval);
        storiesIntervalStart();
    }, intervalTime);
    lastIntervalStart = Date.now();
}

window.storiesIntervalPause = function storiesIntervalPause() {
    clearInterval(interval);
    var elapsed = Date.now() - lastIntervalStart;
    remainingTime = intervalTime - elapsed;
}

window.storiesIntervalResume = function storiesIntervalResume() {
    intervalTime = remainingTime;
    storiesIntervalStart();
}