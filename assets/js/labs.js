$('#lab_stats_fr').dataTable({
    responsive: true, //<B>lTfgtip
    dom: "<'row '" +
            "<'col-md-2'l>" +
            "<'col-md-6'B>" +
            "<'col-md-4 right'f>" +
            ">" +
            "<'row dt-table'" +
            "<'col-md-12'tr>" +
            ">" +
            "<'row'" +
            "<'left col-md-6'i>" +
            "<'col-md-6 right'p>" +
            ">",
    buttons: [
        {
            text: 'Exporter au format CSV',
            extend: 'csvHtml5',
            title: 'Lab_Stat_EID'
        }, {
            text: 'Exporter vers Excel',
            extend: 'excelHtml5',
            title: 'Lab_Stat_EID'
        }, {
            extend: 'colvis',
            collectionLayout: 'fixed three-column'
        }
    ],
    language: {
        decimal: ",",
        thousands: " ",
        processing: "Traitement en cours...",
        search: "Rechercher&nbsp;:",
        lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
        info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
        infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        infoPostFix: "",
        loadingRecords: "Chargement en cours...",
        zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
        emptyTable: "Aucune donnée disponible dans le tableau",
        paginate: {
            first: "Premier",
            previous: "Pr&eacute;c&eacute;dent",
            next: "Suivant",
            last: "Dernier"
        },
        aria: {
            sortAscending: ": activer pour trier la colonne par ordre croissant",
            sortDescending: ": activer pour trier la colonne par ordre décroissant"
        },
        buttons: {
            colvis: 'Changer des colonnes'
        }
    }
});

$('#lab_stats_en').dataTable({
    responsive: true, //<B>lTfgtip
    dom: "<'row '" +
            "<'col-md-2'l>" +
            "<'col-md-6'B>" +
            "<'col-md-4 right'f>" +
            ">" +
            "<'row dt-table'" +
            "<'col-md-12'tr>" +
            ">" +
            "<'row'" +
            "<'left col-md-6'i>" +
            "<'col-md-6 right'p>" +
            ">",
    buttons: [
        {
            text: 'Export to CSV',
            extend: 'csvHtml5',
            title: 'Lab_Stat_EID'
        }, {
            text: 'Export to Excel',
            extend: 'excelHtml5',
            title: 'Lab_Stat_EID'
        }, {
            extend: 'colvis',
            collectionLayout: 'fixed three-column'
        }
    ],
    language: {
        decimal: ".",
        processing: "Processing...",
        search: "Search:",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
        infoPostFix: " ",
        thousands: ",",
        loadingRecords: "Loading...",
        zeroRecords: "No matching records found",
        emptyTable: "No data available in table",
        paginate: {
            first: "First",
            previous: "Previous",
            next: "Next",
            last: "Last"
        },
        aria: {
            sortAscending: ": activate to sort column ascending",
            sortDescending: ": activate to sort column descending"
        },
        buttons: {
            colvis: 'Change columns'
        }
    }
});

$('#select_age').val(0);
$('#select_plateforme').val(0);
$('#select_plateforme_tat').val(0);

$("#select_age").on("change", function () {
    $('.loader').removeClass('hidden');
    var pcr = 0;
    var age = $('#select_age').val();
    var plateforme_id = $('#select_plateforme').val();
    if (!plateforme_id) {
        plateforme_id = 0;
    }
    var height = $('#card_labs_stat_ages').css("height");
    $.post('labs/labs_stat_age/' + plateforme_id + '/' + age + '/' + pcr, null, function (data) {
        $('#card_labs_stat_ages').css({
            height: height
        });
        $('#card_labs_stat_ages').html(data);
        $('.loader').addClass('hidden');
    });
});

$('#select_plateforme').on("change", function () {
    $("#select_age").change();
});

$("#select_plateforme_tat").on("change", function () {
    $('.loader').removeClass('hidden');
    var plateforme_id = $('#select_plateforme_tat').val();
    if (!plateforme_id) {
        plateforme_id = 0;
    }
    var height = $('#card_labs_stat_ages').css("height");
    $.post('labs/labs_tat/' + plateforme_id, null, function (data) {
        $('#card_labs_tat').css({
            height: height
        });
        $('#card_labs_tat').html(data);
        $('.loader').addClass('hidden');
    });
});