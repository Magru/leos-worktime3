/*
* Workday - A time clock application for employees
* URL: https://codecanyon.net/item/workday-a-time-clock-application-for-employees/23076255
* Support: official.codefactor@gmail.com
* Version: 5.0
* Author: Brian Luna
* Copyright 2022 Codefactor
*/
(function() {
    'use strict';

    $('.select-search').select2({
        theme: "bootstrap-5",
    });

    $('.select-search-sm').select2({
        theme: "bootstrap-5",
        dir: "rtl",
        containerCssClass: "select2--small", // For Select2 v4.0
        selectionCssClass: "select2--small", // For Select2 v4.1
        dropdownCssClass: "select2--small",
    });
})();