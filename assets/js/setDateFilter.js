module.exports = function (path, locale) {
    $('.loader').removeClass('hidden');
    var startDate = $('#startDate_' + locale).val();
    var endDate = $('#endDate_' + locale).val();
    $.ajax({
        type: "POST",
        url: path,
        data: {startDate: startDate, endDate: endDate},
        success: function (d) {
            location.reload();
        }
    });
};
