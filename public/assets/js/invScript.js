$(document).ready(function(){
    $('#btn-itemFilters').on('click', function(){
        $('.item-filters').toggle();
    });

  $('.item-filters li a').on('click', function(){
    $('tr.hidden').removeClass('hidden');
    var filter = $(this).attr('data-sort');
    console.log('Filter ' + filter + ' selected.')

    if(filter == 'stk') {
      $('table > tbody > tr').each(function () {
        var thisCode = $(this).text().toLowerCase();        
        if (!$(this).closest('tr').hasClass('criticalStock')) {
          $(this).closest('tr').addClass('hidden');
        }
      });
      console.log('Critical items hidden');
    } else if(filter == 'exp') {
      $('table > tbody > tr').each(function () {
        var thisCode = $(this).text().toLowerCase();        
        if (!$(this).closest('tr').hasClass('expiring')) {
          $(this).closest('tr').addClass('hidden');
        }
      });
      console.log('Expired items hidden');
    }
  });
});