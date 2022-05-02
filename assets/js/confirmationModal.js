/*
* Workday - A time clock application for employees
* URL: https://codecanyon.net/item/workday-a-time-clock-application-for-employees/23076255
* Support: official.codefactor@gmail.com
* Version: 5.0
* Author: Brian Luna
* Copyright 2022 Codefactor
*/
(function() {
    "use strict";
    
    $('.btnDelete').on('click', function() {
        var url;
        url = $(this).attr("data-url");
        $('.modal_URL').attr("href", url);
    });
})();
