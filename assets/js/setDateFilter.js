module.exports = function (locale) {
    var startDate = $('#startDate_' + locale).val();
    var endDate = $('#endDate_' + locale).val();
    $.ajax({
        type: "POST",
        url: '/' + locale + '/date_filter',
        data: {startDate: startDate, endDate: endDate},
        success: function (d) {
            location.reload();
        }
    });
};
