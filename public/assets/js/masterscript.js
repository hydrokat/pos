$.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      }
  });

$(".alert").on('click', function(){
    $(this).hide();
});

var menu_open = 0;
$("#menu-toggle").on('click', function(){
    if(!menu_open){
        $(".sidebar").slideToggle();
        $(this).text('Hide Sidebar');
        $(".navbar-toggle").click();
        menu_open = 1;
    } else {
        $(".sidebar").slideToggle();
        $(this).text('Show Sidebar');
        $(".navbar-toggle").click();
        menu_open = 0;
    }
});

$('body').on('focus', ".datepicker", function(){
    $(this).datepicker({
        changeMonth: true,
        changeYear: true
    });
})

$('body').on('click', ".nav-tabs .tab-item", function(){
    $(this).parent().find('.active').removeClass('active');
    $(this).addClass('active');
});

$( "#dFrom" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "yy-mm-dd",
    onClose: function( selectedDate ) {
        $("#dTo").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: selectedDate
        });
    }
});

$('.datepicker').datepicker({
    dateFormat: "yy-mm-dd",
    changeYear: true,
    changeMonth: true,
});

$('.expiry-picker').datepicker({
      dateFormat: "yy-mm-dd",
      changeYear: true,
      changeMonth: true,
      minDate: 0
  });

function printReport() {
    $("#table-report").clone().appendTo("#div-report");
    window.print();
}

$(document).tooltip();

$('.isTooltip').on('click', function(e){
    e.preventDefault();
    $(this).mouseover();
});

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}