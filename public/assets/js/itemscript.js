$(document).ready(function(){

  $("#ui-datepicker-div").css("z-index", "9999");

  $.fn.modal.Constructor.prototype.enforceFocus = function() {};

  function encodeURL(inputData) {
    var newUrl = escape(newUrl);
    newUrl = encodeURIComponent(inputData);
    newUrl = inputData;
    return newUrl;
  }

  $(document).on('click', ".btn-searchItem", function(e){
    var msg = "This item is in "
    bootbox.alert(msg);
  });

  $(document).on('click', ".btn-editItem", function(e){
    e.preventDefault();
    var tr = $(this).parent().prev().closest('tr');
    var pcode = encodeURL(tr.find('td:nth-child(1)').html());
    var lot   = encodeURL(tr.find('td:nth-child(2)').html());
    var exp   = encodeURL(tr.find('td:nth-child(3)').html());

    //$.get('item/' + pcode + '/' + lot + '/' + exp, function(data){
      $.post('item/postitem', {pcode:pcode, lot:lot, exp:exp},function(data){
      if(data != null) {
        console.log(data);

        var modal = $('#modal-edit');
        modal.find('#input-pcode').val(data.p_code);
        modal.find('#input-name').val(data.name);
        modal.find('#input-cat').val(data.category);
        modal.find('#input-desc').val(data.desc);
        modal.find('#input-lot').val(data.lotNo);
        modal.find('#input-exp2').val(data.expiry);
        modal.find('#input-oldLot').val(data.lotNo);
        modal.find('#input-oldExp').val(data.expiry);
        modal.find('#input-invThresh').val(data.inventory_threshold);
        modal.find('#input-qtyPkg').val(data.packages);
        modal.find('#input-qtyRet').val(data.retail);
        modal.find('#input-pAcq').val(data.acquisition_price);
        modal.find('#input-pRet').val(data.price_retail);
        modal.find('#input-pPkg').val(data.price_package);
      }
    }, "JSON");
  });

  $(document).on('click', ".btn-deleteItem", function(e){
    e.preventDefault();
    var btn = $(this);

    var pcode = $(this).attr('data-pcode');
    var exp = $(this).attr('data-exp');
    var lot = $(this).attr('data-lot');
    console.log("Code:" + pcode);

    bootbox.confirm("Are you sure to delete this item?", function(resp) {
      console.log("Confirm result: " + resp);
      if(resp){
        $.ajax({
          url: "item/delete/" + pcode + "/" + lot + "/" + exp,
        }).done(function(data) {
          console.log(data);
          bootbox.dialog({
          message: data,
          title: "Product Delete",
          buttons: {
            success: {
              label: "Undo",
              className: "btn-warning",
              callback: function() {
                $.ajax({
                  url: "item/undodelete/" + pcode + "/" + lot + "/" + exp
                }).done(function(data){
                  bootbox.alert(data);
                });
              }
            },
            main: {
              label: "Okay",
              className: "btn-primary",
            }
          }
        });
        });

        btn.parent().prev().closest('tr').hide();
      }
    });
  });

  $(document).on('click', "#addItemReminder", function(e){
    e.preventDefault();

    var msg = "<ul>";
    msg += "<li>Product codes should be unique.</li>";
    msg += "<li>Product codes are not recyclable.</li>";
    msg += "</ul>";

    bootbox.dialog({
      title: "Reminders",
      message: msg,
      buttons: {
        success: {
          label: "Okay, got it!",
          className: "btn-success"
        },
      }
    });
  });

  $('ul').on('click', '.tab-item a', function(){
    var selectedCode = $(this).attr('data-code');
    $('tr.hidden').removeClass('hidden');
    $('table > tbody > tr > td:nth-child(4)').each(function () {
        var thisCode = $(this).text().toLowerCase();
        if (thisCode != selectedCode && selectedCode != 'all') {
          $(this).closest('tr').addClass('hidden');
        }
    });
  });

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
        if (!$(this).closest('tr').hasClass('bg-red')) {
          $(this).closest('tr').addClass('hidden');
        }
      });
      console.log('Critical items hidden');
    } else if(filter == 'exp') {
      $('table > tbody > tr').each(function () {
        var thisCode = $(this).text().toLowerCase();        
        if (!$(this).closest('tr').hasClass('bg-yellow')) {
          $(this).closest('tr').addClass('hidden');
        }
      });
      console.log('Expired items hidden');
    }

  });

  $('#txt-search').on('change', function(){
    var pcode = $(this).val();
    $('table > tbody > tr > td:nth-child(1)').each(function () {
      var thisCode = $(this).text().toLowerCase();
      if (thisCode.indexOf(pcode) < 0) {
        $(this).closest('tr').addClass('hidden');
      } else {
        $(this).closest('tr').removeClass('hidden');
      }
    });
  });

  $('#form-editItem').on('submit', function(e){
      var thisForm = $('#form-editItem');
      if(e.originalEvent !== undefined){
          e.preventDefault();
          bootbox.confirm("Are you sure you want to edit this item?", function(resp) {
              if(resp) {
                  thisForm.submit();
              }
          });
      }
  });
});