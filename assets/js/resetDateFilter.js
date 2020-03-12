module.exports = function (path) {
    $.ajax({
        type: "POST",
        url: path,
        success: function (d) {
            location.reload();
        }
    });
};
