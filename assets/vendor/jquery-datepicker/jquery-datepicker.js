$( function() {

    function padTo2Digits(num) {
        return String(num).padStart(2, '0');
    }

    $( "#datepicker" ).datepicker({
        inline: true,
        showOtherMonths: true,
        monthNames: ['ינואר', 'פברואר', 'מרץ', 'אפריל', 'מאי', 'יוני',
            'יולי', 'אוגוסט', 'ספטמבר', 'אוקטובר', 'נובמבר', 'דצמבר'],
        dayNamesMin: ['א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ש'],
        firstDay: 0,
        isRTL: true,
        navigationAsDateFormat: true,
        nextText: 'MM',
        appendText: "(yyyy-mm-dd)",
        prevText: 'MM',
        dateFormat: "yy-mm-dd",
        beforeShow: function(inst){

        },
        onChangeMonthYear: function (year, month, inst) {
            $(".active-month").attr('data-month', month).attr('data-year', year);
        },
        beforeShowDay : function(date){
            let res = [true, '', ''];
            let d;
            let classes = '';
            console.log($(this));
            for(var i in entries) {
                d = entries[i].date;
                if(date.getFullYear() == d.getFullYear() && date.getMonth() == d.getMonth() && date.getDate() == d.getDate()) {

                    classes += ' has-entry ';

                    if(entries[i].h_125){
                        classes += ' h_125 '
                    }

                    if(entries[i].h_150){
                        classes += ' h_150 '
                    }

                    if(entries[i].timein){
                        classes += ' has_in '
                    }

                    if(entries[i].timeout){
                        classes += ' has_out '
                    }

                    const hours_in = entries[i].timein ? padTo2Digits(entries[i].timein.getHours()) +  ":" + padTo2Digits(entries[i].timein.getMinutes())  : null;
                    const hours_out = entries[i].timeout ? padTo2Digits(entries[i].timeout.getHours()) +  ":" + padTo2Digits(entries[i].timeout.getMinutes()) : null;

                    res = [true, classes, entries[i].realhours + '-' +  hours_in + '-' + hours_out];
                }
            }

            return res;
        },
    });



    $('.has-entry').each(function(){
        const title = $(this).attr('title');
        const title_split = title.split('-');
        const timein = title_split[1];
        const timeout = title_split[2];

        $(this).find('a').append('<span class="hours-info">in:'+ timein + ' - ' + 'out:' + timeout + '</span>')
    })

    if ($.fn.datepicker) {

    }

} );
