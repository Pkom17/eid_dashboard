module.exports = function (locale) {
    var startDate = $('#startDate_' + locale).val();
    var endDate = $('#endDate_' + locale).val();
    $.ajax({
        type: "POST",
        url: '/' + locale + '/reset_date_filter',
        success: function (d) {
            location.reload();
        }
    });
};
