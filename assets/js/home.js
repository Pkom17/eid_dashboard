$("#select_pcr").on("change", function () {
    var pcr = $(this).val();
    $('.loader').removeClass('hidden');
    $.post('tests_trends/' + pcr, null, function (data) {
        $('#card_test_trends').html(data);
        $('.loader').addClass('hidden');
    });
});
