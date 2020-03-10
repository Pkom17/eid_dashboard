$("#select_pcr").on("change", function () {
    var pcr = $(this).val();
    $.post('tests_trends/' + pcr, null, function (data) {
        $('#card_test_trends').html(data);
    });
});
