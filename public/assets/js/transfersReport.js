$(document).ready(function(){
    $('#btn-itemFilters').on('click', function(){
        $('.item-filters').toggle();
    });

  $('.item-filters li a').on('click', function(){
    $('tr.hidden').removeClass('hidden');
    var filter = $(this).attr('data-sort');
    console.log('Filter ' + filter + ' selected.')

    if(filter == 'in') {
      $('table > tbody > tr > td:nth-child(3)').each(function () {
        var iio = $(this).text().toLowerCase();
        if (iio != 'in') {
          $(this).closest('tr').addClass('hidden');
        }
      });
    } else if(filter == 'out') {
      $('table > tbody > tr > td:nth-child(3)').each(function () {
        var iio = $(this).text().toLowerCase();
        if (iio != 'out') {
          $(this).closest('tr').addClass('hidden');
        }
      });
    }
  });
});