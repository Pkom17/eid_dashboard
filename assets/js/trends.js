
 $('#org_region').val(0);
 $('#org_district').val(0);
  $('#org_site').val(0);
$('#select_age_year').val(0);
 $('#select_age_quarter').val(0);
 $('#select_age_month').val(0);

$("#select_pcr_year").on("change", function () {
    var pcr = $(this).val();
    var age = $('#select_age_year').val();
    var region_id = $('#org_region').val();
    var district_id = $('#org_district').val();
    var site_id = $('#org_site').val();
    if (!region_id) {
        region_id = 0;
    }
    if (!district_id) {
        district_id = 0;
    }
    if (!site_id) {
        site_id = 0;
    }
    var height = $('#card_trends_year').css("height");
    $.post('tests_trends_year/' + region_id + '/' + district_id + '/' + site_id + '/' + age + '/' + pcr, null, function (data) {
        $('#card_trends_year').css({
            height: height
        });
        $('#card_trends_year').html(data);
    });
});
$("#select_pcr_quarter").on("change", function () {
    var pcr = $(this).val();
    var age = $('#select_age_quarter').val();
    var region_id = $('#org_region').val();
    var district_id = $('#org_district').val();
    var site_id = $('#org_site').val();
    if (!region_id) {
        region_id = 0;
    }
    if (!district_id) {
        district_id = 0;
    }
    if (!site_id) {
        site_id = 0;
    }
    var height = $('#card_trends_quarter').css("height");
    $.post('tests_trends_quarter/' + region_id + '/' + district_id + '/' + site_id + '/' + age + '/' + pcr, null, function (data) {
        $('#card_trends_quarter').css({
            height: height
        });
        $('#card_trends_quarter').html(data);
    });
});
$("#select_pcr_month").on("change", function () {
    var pcr = $(this).val();
    var age = $('#select_age_month').val();
    var region_id = $('#org_region').val();
    var district_id = $('#org_district').val();
    var site_id = $('#org_site').val();
    if (!region_id) {
        region_id = 0;
    }
    if (!district_id) {
        district_id = 0;
    }
    if (!site_id) {
        site_id = 0;
    }
    var height = $('#card_trends_month').css("height");
    // $('#card_trends_month').load('tests_trends_month/' + region_id + '/' + district_id + '/' + site_id + '/' + age + '/' + pcr);
    $.post('tests_trends_month/' + region_id + '/' + district_id + '/' + site_id + '/' + age + '/' + pcr, null, function (data) {
        $('#card_trends_month').css({
            height: height
        });
        $('#card_trends_month').html(data);
    });
});

$('#select_age_year').on("change", function () {
    $("#select_pcr_year").change();
});
$('#select_age_quarter').on("change", function () {
    $("#select_pcr_quarter").change();
});
$('#select_age_month').on("change", function () {
    $("#select_pcr_month").change();
});
//refresh graphs on organization changes
$('#org_region').on("change", function () {
    $('#org_site').val(0);
    $('#org_district').val(0);
    $("#select_pcr_year").change();
    $("#select_pcr_quarter").change();
    $("#select_pcr_month").change();
});
$('#org_district').on("change", function () {
    $('#org_site').val(0);
    $("#select_pcr_year").change();
    $("#select_pcr_quarter").change();
    $("#select_pcr_month").change();
});
$('#org_site').on("change", function () {
    $("#select_pcr_year").change();
    $("#select_pcr_quarter").change();
    $("#select_pcr_month").change();
});

