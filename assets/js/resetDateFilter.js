module.exports = function (path) {
    $('.loader').removeClass('hidden');
    $.ajax({
        type: "POST",
        url: path,
        success: function (d) {
            location.reload();
        }
    });
};
