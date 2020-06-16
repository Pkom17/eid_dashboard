/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import $ from 'jquery';
global.$ = global.jQuery = $;
import '../scss/app.scss';
import '../css/main.css';
import 'bootstrap';
require('webpack-jquery-ui');
require('webpack-jquery-ui/css');

import 'datatables.net-dt/js/dataTables.dataTables.min.js'
import 'datatables.net-buttons-dt/js/buttons.dataTables.min.js'
import 'datatables.net-buttons-dt/css/buttons.dataTables.min.css'

import 'datatables.net-bs4/js/dataTables.bootstrap4.min.js'

import 'datatables.net-buttons/js/dataTables.buttons.min.js'
import 'datatables.net-buttons/js/buttons.html5.min.js'
import 'datatables.net-buttons/js/buttons.colVis.min.js'

import 'datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js'

import jsZip from 'jszip';
window.JSZip = jsZip;

import 'select2';
import 'select2/dist/css/select2.css';
var Highcharts = require('highcharts');
require('highcharts/modules/exporting')(Highcharts);
global.Highcharts = Highcharts;
const setDateFilter = require('./setDateFilter');
const resetDateFilter = require('./resetDateFilter');
global.setDateFilter = setDateFilter;
global.resetDateFilter = resetDateFilter;
global.getVisitInfos = getVisitInfos;
global.$.datepicker = $.datepicker;


(function ($) {
    "use strict"; // Start of use strict

    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        }
        ;
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        }
        ;
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                    delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });
    // Scroll to top button appear
    $(document).on('scroll', function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function (e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

})(jQuery); // End of use strict

function getVisitInfos(locale) {
    var this_page = location.pathname + location.search;
    $.get("visit/infos", {page: this_page}).done(function (data) {
        $('#total_visit').text(data['total_visit']);
        $('#page_views').text(data['page_views']);
        $('#online').text(data['online']);
    }).always(function (data) {
        setTimeout(function(){getVisitInfos(locale);}, 10000);
    });
}

$(document).ready(function () {
    $.datepicker.regional['en'] = {
        closeText: 'Done',
        prevText: 'Prev',
        nextText: 'Next',
        currentText: 'Today',
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'],
        monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        weekHeader: 'Wk',
        dateFormat: 'mm/dd/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $('#startDate_fr,#endDate_fr').datepicker({
        closeText: "Fermer",
        prevText: "Précédent",
        nextText: "Suivant",
        currentText: "Aujourd'hui",
        monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
            "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
        monthNamesShort: ["janv.", "févr.", "mars", "avr.", "mai", "juin",
            "juil.", "août", "sept.", "oct.", "nov.", "déc."],
        dayNames: ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"],
        dayNamesShort: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
        dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
        weekHeader: "Sem.",
        dateFormat: "MM yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });

    $('#startDate_en,#endDate_en').datepicker({
        closeText: 'Done',
        prevText: 'Prev',
        nextText: 'Next',
        currentText: 'Today',
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'],
        monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        weekHeader: 'Wk',
        dateFormat: 'MM yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });


});
