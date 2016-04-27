$(document).foundation();
$(document).ready(function () {
    var $form = $('#templateForm');
    $form.keyup(function () {
        clearTimeout($.data(this, 'timer'));
        var wait = setTimeout(saveDraft, 500);
        $(this).data('timer', wait);
    });

    $form.bind("paste", function () {
        clearTimeout($.data(this, 'timer'));
        var wait = setTimeout(saveDraft, 500);
        $(this).data('timer', wait);
    });

    function saveDraft() {
        var matchers = window.location.href.match(/app_.*\.php/i);
        var envUrl = (matchers === null) ? '' : '/' + matchers[0];
        $.ajax({
            method: 'PUT',
            url: envUrl + '/api/v1/drafts/' + $('#form_templateid').val(),
            data: {
                name: $('#form_name').val(),
                plaintext_content: $('#form_plaintext_content').val(),
                html_content: $('#form_html_content').val()
            },
            context: document.body
        }).done(function (msg) {
        });
    }
});

$('#status').fadeOut(); // will first fade out the loading animation
$('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
$('body').delay(350).css({
    'overflow': 'visible'
});
$('.counter').counterUp();

//Datepicker
var dateStart = $('#dateFrom').fdatepicker().on('changeDate', function (ev) {
    if (ev.date.valueOf() > dateEnd.date.valueOf()) {
        var newDate = new Date(ev.date);
        newDate.setDate(newDate.getDate() + 1);
        dateEnd.update(newDate);
    }
    dateStart.hide();
    $('#dpd2')[0].focus();
}).data('datepicker');
var dateEnd = $('#dateTo').fdatepicker({
    onRender: function (date) {
        return date.valueOf() <= dateStart.date.valueOf() ? 'disabled' : '';
    }
}).on('changeDate', function (ev) {
    dateEnd.hide();
}).data('datepicker');