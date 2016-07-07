// what the heck?!
//$(function(){$().onloadEvents();});

// extend :contains to be case-insensitive; via http://stackoverflow.com/questions/187537/
jQuery.expr[':'].contains = function(a,i,m){return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;};

$('#filter').keyup(function(){
    if($(this).val().length <= 1){
        $(".def").css('opacity','1')
    } else {
        $(".def").css('opacity','0.2');
        var array_of_strings = $(this).val().split(' ');
        // console.log(array_of_strings);
        $.each(array_of_strings, function(index, value) { 
            $(":contains("+value+")").parents(".def").css('opacity','1');
        });
    }
});