import $ from './jquery';

$(document).ready(function () {

    loadData();

    $(document).on('click', '[data-target="#eventModal"]', function (e) {
        if(!e.ctrlKey) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            initEventModalTrigger($(this));
            $('#eventModalTrigger').click();
        }
    });

    $(document).on('click', '.is-live', function() {
        $('#tabStreamTrigger').click();
    });

    $(document).on('click', '.twitch-link.chat', function() {

        $(this).toggleClass('active');

        $(this).find('i.fa-solid').toggleClass('fa-toggle-on');
        $(this).find('i.fa-solid').toggleClass('fa-toggle-off');

        if ($(this).hasClass('active')) {
            $('#liveChatParent').css('margin-right', '');
            $('.twitch-chat-placeholder').css('margin-right', '');
            $('.videos').removeClass('chat-inactive');
        } else {
            $('#liveChatParent').css('margin-right', '-' + $('#liveChatParent').outerWidth() + 'px');
            $('.twitch-chat-placeholder').css('margin-right', '-' + $('#liveChatParent').outerWidth() + 'px');
            $('.videos').addClass('chat-inactive');
        }

        $.ajax({
            method: 'post',
            data: {
                toggle:$(this).hasClass('active')
            },
            url: appUrl + '/twitch/chat/toggle'
        });
    });

    $(document).on('change', '.videos select[name="type"]', function () {
        filterVideos(appUrl + '/twitch/videos/filter', {type:$(this).val(), search:''}, true);
    });

    $(document).on('change', '.videos select[name="type_top"]', function () {
        filterVideos(appUrl + '/twitch/videos/filter', {type_top:$(this).val()}, true);
    });

    $(document).on('change', '.videos input[name="search"]', function () {
        filterVideos(appUrl + '/twitch/videos/filter', {search:$(this).val()}, true);
    });

    $(window).on('resize', function () {
        if ($('#liveStreamPlayer').length) {
            $('#liveStreamPlayer').height($('#liveStreamPlayer').width() * 9 / 16);
        }
        if ($('.post-details-video').length) {
            $('.post-details-video').height($('.post-details-video').width() * 9 / 16);
            $('.post-details-video').css('max-height', 'calc(' +
                $(window).height() + 'px - ' +
                $('.modal-dialog:visible').css('padding-top') + ' - ' +
                $('.modal-dialog:visible').css('padding-bottom') + ' - ' +
                $('.modal-header:visible').outerHeight() + 'px' +
            ')');
        }
        if ($('.twitch-link.chat').length && !$('.twitch-link.chat').hasClass('active')) {
            $('#liveChatParent').css('margin-right', '-' + $('#liveChatParent').outerWidth() + 'px');
            $('.twitch-chat-placeholder').css('margin-right', '-' + $('#liveChatParent').outerWidth() + 'px');
        }
        if ($('.schedule .table').length) {
            if ($(window).width() < 1600) {
                $('.schedule .table').css('transform', 'scale(' + ( $(window).width() / 1600 ) + ')');
                $('.schedule .table').css('transform-origin', 'left top');
                $('.schedule .table').css('width', ( 1600 / $(window).width() * 100 ) + '%');
            } else {
                $('.schedule .table').css('transform', '');
                $('.schedule .table').css('transform-origin', '');
                $('.schedule .table').css('width', '');
            }
        }
    });

});

function initTabs() {
    let activeTab = $('button[data-bs-toggle="tab"].active');

    if (activeTab.length) {

        $('button[data-bs-toggle="tab"]').each(function(index, el) {
            ajaxLoaderInfinityUrls[$(el).data('loader')] = $(el).data('route');
            el.addEventListener('shown.bs.tab', event => {
                window.history.pushState("", "", appUrl + '/twitch?tab=' + $(el).data('name'));
                infinityLoaderInit(
                    $(el).data('scroller'),
                    $(el).data('wrapper'),
                    $(el).attr('id') === 'tabStreamTrigger'
                        ? $($(el).data('wrapper')).offset().top
                        : 0,
                    $(el).data('loader'),
                    $(el).attr('id') === 'tabStreamTrigger'
                        ? initStream
                        : ( $(el).attr('id') === 'tabScheduleTrigger'
                            ? initSchedule
                            : undefined )
                );
                $(window).trigger('resize');
            });
        });

        window.history.pushState("", "", appUrl + '/twitch?tab=' + activeTab.data('name'));
        infinityLoaderInit(
            activeTab.data('scroller'),
            activeTab.data('wrapper'),
            activeTab.attr('id') === 'tabStreamTrigger' ? $(activeTab.data('wrapper')).offset().top : 0,
            activeTab.data('loader'),
            activeTab.attr('id') === 'tabStreamTrigger'
                ? initStream
                : ( activeTab.attr('id') === 'tabScheduleTrigger'
                ? initSchedule
                : undefined )
        );

        setTimeout(() => {
            $('.nav-tabs.d-none').removeClass('d-none');
        }, '200');
    }
}

function initStream() {

    if ($('#liveStreamPlayer').length && typeof Twitch != 'undefined') {
        calcStreamDuration();

        $(window).trigger('resize');

        var muted = false;
        var embed = new Twitch.Embed("liveStreamPlayer", {
            width: "100%",
            height: "100%",
            theme: $('meta[name="theme"]').attr('content'),
            channel: $('#username').val(),
            layout: "video",
            autoplay: true,
            muted: false,
        });
        embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
            var player = embed.getPlayer();
            player.setVolume(1);
            // player.setMuted(muted);
        });

        $('button[data-bs-toggle="tab"][data-bs-target="#tabStreamContent"]').each(function(index, el) {
            el.addEventListener('hide.bs.tab', event => {
                muted = embed.getMuted();
                embed.setMuted(true);
            });
            el.addEventListener('show.bs.tab', event => {
                embed.setMuted(muted);
            });
        });

        Echo.channel('twitch.stream')
            .listen('.update', (data) => {
                if (data.stream) {
                    if (!$('.stream-title').length) {
                        $('.stream').html('');
                        loadDataPart('stream');
                    } else {
                        $('.stream-title').html(data.stream.title);
                        $('.stream-game-name').html(data.stream.game_name);
                        $('.stream-tag').remove();
                        $.each($.parseJSON(data.stream.tags), function(index, el) {
                            $('.stream-tags').append('' +
                                '<a href="https://www.twitch.tv/directory/all/tags/' + el + '"' +
                                '   class="stream-tag text-dark text-decoration-none text-hover my-1 me-2"' +
                                '   target="_blank">' +
                                '' + el +
                                '</a>'
                            );
                        });
                        $('.stream-viewer-count').html(parseFloat(data.stream.viewer_count).toLocaleString('en-EN'));
                        $('.stream-duration').html(data.stream.duration);
                        $('.stream-duration').data('seconds', data.stream.duration_seconds);
                        clearInterval(streamDurationInterval);
                        calcStreamDuration();
                    }
                } else {
                    $('.stream').html('');
                    loadDataPart('stream');
                }
            });
    }
}

function initSchedule(response) {

    let data = response.data;
    let currentDate = new Date();

    if ($('.hidden-event-modal-trigger').length) {
        currentDate = new Date($('.hidden-event-modal-trigger').data('date'));
    }


    $(document).on('click', '.calendar-nav-today', function() {
        currentDate = new Date();
        renderScheduleCalendar(data, currentDate);
    });

    $(document).on('click', '.calendar-nav-prev', function() {
        currentDate.setDate(currentDate.getDate() - 7);
        renderScheduleCalendar(data, currentDate);
    });

    $(document).on('click', '.calendar-nav-next', function() {
        currentDate.setDate(currentDate.getDate() + 7);
        renderScheduleCalendar(data, currentDate);
    });

    renderScheduleCalendar(data, currentDate);

    initEventModalEvents();
}

function renderScheduleCalendar(data, date) {
    let startOfWeek = new Date(date);
    let day = startOfWeek.getDay();
    let diff = (day <= 0 ? -6 : 1) - day;
    startOfWeek.setDate(date.getDate() + diff);

    $('.calendar-nav-week-info').html('Week of ' + startOfWeek.toDateString());

    let calendarContent = $('#calendarContent table');
    calendarContent.empty();

    let range = setScheduleDataRange(data, startOfWeek);

    let calendarContentRow = $('<tr></tr>');
    calendarContentRow.append($('<th class="border-bottom p-2" style="width: 4rem;"></th>'));

    let columns = setScheduleHourColumns(range, calendarContentRow);

    calendarContent.append(calendarContentRow);

    let locale = 'en-EN';

    for (let i = 0; i < 7; i++) {
        let dayDate = new Date(startOfWeek);
        dayDate.setDate(startOfWeek.getDate() + i);

        let calendarContentRow = $('<tr ' + ( dayDate.toDateString() === new Date().toDateString() ? 'class="today"' : '' ) + '></tr>');
        calendarContentRow.append($(
            '<td class="calendar-day">' +
                dayDate.toLocaleDateString(locale, { weekday:'short', day:'numeric', month:'numeric' }) +
            '</td>'
        ));

        let dayEvents = getScheduleDayData(data, dayDate);

        if (dayEvents.length) {

            dayEvents.forEach(event => {
                let startTimeHelper = new Date(event.start_time);
                startTimeHelper.setHours(startTimeHelper.getHours() + 2);
                let startTime = startTimeHelper.toLocaleTimeString(locale, { weekday:'long', day:'numeric', month:'long', year:'numeric', hour:'numeric', minute: 'numeric' });

                let endTimeHelper = new Date(event.end_time);
                endTimeHelper.setHours(endTimeHelper.getHours() + 2);
                let endTime = endTimeHelper.toLocaleTimeString(locale, { hour:'numeric', minute: 'numeric' });

                let columnsCounter = 0;
                columns.some(function (value, index, array) {
                    if (startTimeHelper.getHours() > value) {
                        columnsCounter++;
                        calendarContentRow.append('<td></td>');
                    } else {
                        return true;
                    }
                });

                let eventElement = $(`
                    <td colspan="${event.duration}">
                        <a href="${appUrl + '/twitch/event/' + event.twitch_id}" 
                            class="event text-decoration-none" 
                            style="width: ${event.duration}" 
                            title="${event.title}"
                            data-target="#eventModal"
                            data-route="${appUrl + '/twitch/event/' + event.twitch_id}"
                            data-id="${event.twitch_id}">
                             <div class="d-flex h-100">
                                <div class="d-flex flex-column overflow-hidden">
                                    <h4 class="m-0 w-100 overflow-hidden text-overflow-ellipsis text-nowrap">${event.title}</h4>
                                    ` + ( event.category_name ? `<div class="mt-auto">${event.category_name}</div>` : `` ) + `
                                    <div class="mt-auto">${startTime} - ${endTime} GMT+2</div>
                                </div>
                                ` + ( event.category_name
                                    ? `<img src="${event.category.box_art_url.replace('{width}', '144').replace('{height}', '192')}"
                                            class="h-100 ms-auto ps-2"
                                            alt="${event.category.name}"
                                            title="${event.category.name}">`
                                    : `` ) + `
                            </div>
                        </a>
                    </td>
                @if($video->game)
                @endif
                `);
                calendarContentRow.append(eventElement);

                columns.some(function (value, index, array) {
                    if (index >= columnsCounter + event.duration) {
                        calendarContentRow.append('<td></td>');
                    }
                })
            });

        } else {

            for (let i = 0; i < $('.calendar-header').length; i++) {
                calendarContentRow.append('<td></td>');
            }
        }

        calendarContent.append(calendarContentRow);
    }
}

function setScheduleDataRange(data, startOfWeek) {
    let range = {};

    range['earliest'] = 24;
    range['latest'] = 0;

    let hasWeekEvents = false;

    for (let i = 0; i < 7; i++) {
        const dayDate = new Date(startOfWeek);
        dayDate.setDate(startOfWeek.getDate() + i);

        const dayEvents = getScheduleDayData(data, dayDate);

        if (dayEvents.length) {
            hasWeekEvents = true;

            dayEvents.forEach(event => {
                let startHour = new Date(event.start_time).getHours() + 2

                if (startHour < range['earliest']) {
                    range['earliest'] = startHour;
                }

                let startDay = new Date(event.start_time).getDay();
                let endDay = new Date(event.end_time).getDay();

                let endHour = new Date(event.end_time).getHours() + 2;
                endHour = (endDay - startDay) * 24 + endHour

                if (endHour > range['latest']) {
                    range['latest'] = endHour;
                }
            });
        }
    }

    if (!hasWeekEvents) {
        range['earliest'] = 12;
        range['latest'] = 22;
    }

    return range;
}

function setScheduleHourColumns(range, calendarContentRow) {
    let columns = [];

    for (let i = 0; i < range['latest'] - range['earliest'] + 2; i++) {

        let hour = (range['earliest'] - 1 + i);

        if (range['earliest'] - 1 === 23 || hour > 24) {
            hour = hour - 24;
        }

        let el = $(
            '<th class="calendar-header text-nowrap border-bottom p-2" style="width: calc(' +
                ( 100 / (range['latest'] - range['earliest'] + 2) ) + '% - ' +
                ( 4 / (range['latest'] - range['earliest'] + 2) ) +
            'rem);">' +
                new Date("0001-01-01 " + ( hour < 0 ? hour + 24 : hour ) + ":00").toLocaleTimeString('en-EN', { hour: "numeric" }) +
            '</th>'
        );

        calendarContentRow.append(el);

        columns.push(hour);
    }
    return columns;
}

function getScheduleDayData(data, dayDate) {
    return data.filter(event => {
        const eventDate = new Date(event.start_time);
        eventDate.setHours(eventDate.getHours() + 2);
        return eventDate.toDateString() === dayDate.toDateString();
    });
}

function filterVideos(url, data, loadVideos) {
    $('.videos').html(elementHtml($('.videos-profile-wrapper')));
    $('.videos').append(infinityLoaderElement('videoFilterLoader'));

    $.ajax({
        method: 'post',
        data: data,
        url: url,
        success: function(response) {
            $('#videoFilterLoader').remove();

            $('.videos').html(response.view);

            if (loadVideos) {
                loadDataPart('videos', infinityLoaderPaginate, '#tabVideosContent');
            }
        }
    });
}

var streamDurationInterval;
function calcStreamDuration() {
    var sec = $('.stream-duration').data('seconds');
    function pad (val) {
        return val > 9 ? val : "0" + val;
    }
    function cut (val) {
        return val > 59 ? cut(val-60) : val;
    }
    streamDurationInterval = setInterval( function(){
        let hours = pad(parseInt((sec+1)/60/60,10));
        let minutes = pad(cut(parseInt((sec+1)/60,10)));
        let seconds = pad(++sec%60);
        if (!isNaN(seconds)) {
            $('.stream-duration').html(hours + ":" + minutes + ":" + seconds);
        }
    }, 1000);
}

function loadData() {
    if ($('#twitchNav').length) {
        initTabs();
    }
}

function loadDataPart(part, callback, callbackParams) {
    ajaxLoaderCall(
        '.' + part,
        part + 'Loader',
        appUrl + '/twitch/' + part,
        callback,
        callbackParams
    );
}

window.initEventModalEvents = function initEventModalEvents() {
    if ($('#eventModal').length) {

        if ($('.hidden-event-modal-trigger').length) {
            $('.hidden-event-modal-trigger').click();
        }

        document.getElementById('eventModal').addEventListener('shown.bs.modal', function () {
            initEvent($('[data-target="#eventModal"][data-id="' + $(this).attr('data-id') + '"]'));
        });

        document.getElementById('eventModal').addEventListener('hide.bs.modal', function () {
            window.history.pushState("", "", $(this).data('close-route'));
        });

        document.getElementById('eventModal').addEventListener('hidden.bs.modal', function () {
            let wrapperSelector = $(this).find('.modal-body-inner');
            $(wrapperSelector).html('');
        });
    }
}

window.initEventModalTrigger = function initEventModalTrigger(trigger) {
    let modalSelector = '#eventModal';
    let wrapperSelector = modalSelector + ' .modal-body-inner';

    $(wrapperSelector).html('');

    $(modalSelector).removeClass('z-index-1001');
    $(modalSelector).data('id', trigger.data('id'));
    $(modalSelector).data('route', trigger.data('route'));
    $(modalSelector).attr('data-id', trigger.data('id'));
    $(modalSelector).attr('data-route', trigger.data('route'));
}

window.initEvent = function initEvent(trigger) {
    let modalSelector = '#eventModal';
    let wrapperSelector = modalSelector + ' .modal-body-inner';
    let loaderId = 'eventModalLoader';

    ajaxLoaderInfinityTriggered[loaderId] = false;
    ajaxLoaderInfinityUrls[loaderId] = trigger.data('route');

    ajaxLoaderCall(
        wrapperSelector,
        loaderId,
        trigger.data('route'),
        initEventCallback,
        trigger
    );

    window.history.pushState("", "", trigger.data('route'));
}

window.initEventCallback = function initEventCallback(response, wrapperSelector, loaderId, url, trigger) {
    $('.event-details-title').html($('.event-details-title-hidden').html());
}