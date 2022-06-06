$( function() {

    function padTo2Digits(num) {
        return String(num).padStart(2, '0');
    }

    function dayInfo(){
        $('.has-entry').each(function(){
            if($(this).hasClass('leave-entry')){
                const title = $(this).attr('title');
                $(this).find('a').append('<span class="hours-info">סוג חופשה: '+title+'</span>');
            }else{
                const title = $(this).attr('title');
                const title_split = title.split('-');
                const timein = title_split[1];
                const timeout = title_split[2];
                const realhours = title_split[3];
                let html = '<span class="hours-info d-flex">';
                html += '<span class="hours-info-inout"> <span><i class="fa-solid fa-right-to-bracket pl-1"></i> '+timein+'</span>'
                html += '<span><i class="fa-solid fa-right-from-bracket pl-1"></i> '+timeout+'</span></span>'
                html += '<span class="add-info"><i class="fa-solid fa-clock"></i> ' + realhours + '</span>'
                html += '</span>';


                $(this).find('a').append(html);
            }

        })
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
            setTimeout(function (){
                dayInfo();
            }, 1000)
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

                    if(entries[i].type === 'workday'){
                        classes += ' workday-entry '
                    }else if(entries[i].type === 'leave'){
                        classes += ' leave-entry '
                    }

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

                    if(entries[i].type === 'workday'){
                        res = [true, classes, entries[i].realhours + '-' +  hours_in + '-' + hours_out + '-' + entries[i].realhours];
                    }else if(entries[i].type === 'leave'){
                        res = [true, classes, entries[i].reason];
                    }


                }
            }

            return res;
        },
    });



    dayInfo();


} );
