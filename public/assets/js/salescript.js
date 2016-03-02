$(document).ready(function(){
  var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
      var matches, substrRegex;
   
      // an array that will be populated with substring matches
      matches = [];
   
      // regex used to determine if a string contains the substring `q`
      substrRegex = new RegExp(q, 'i');
   
      // iterate through the pool of strings and for any string that
      // contains the substring `q`, add it to the `matches` array
      $.each(strs, function(i, str) {
        if (substrRegex.test(str)) {
          // the typeahead jQuery plugin expects suggestions to a
          // JavaScript object, refer to typeahead docs for more info
          matches.push({ value: str });
        }
      });
   
      cb(matches);
    };
  };

  var codes = [];

  $.get('pcodes', function(data){
    $.each(data, function( key, value ) {
      codes[key] = value;
      //console.log( key + ": " + value );
    });
  }, "json");

  //console.log(codes);

  $('#pcode .typeahead').typeahead({
    hint: true,
    highlight: true,
    minLength: 1
  },
  {
    name: 'codes',
    displayKey: 'value',
    source: substringMatcher(codes)
  });

  /*Load Cart*/

  $.post('sale/cart', function(data){
    //console.log(data);
    if(data != null){
      $('#input-invoice').val(data);

      var table="<tr><th>Code</th><th>Qty</th><th>Amt</th><th>Type</th><th>Dsc</th><th>Lot</th><th>Exp</th><th>Remove</th></tr>";
      var st = 0;
      var d = 0;

      $.each(data, function(key, value){
          st += parseFloat(value.amt);
          d += parseFloat(value.dsc);
          table += "<tr>";
          table += "<td>" + value.code + "</td>";
          table += "<td>" + value.qty + "</td>";
          table += "<td>" + value.amt + "</td>";
          table += "<td>" + value.type + "</td>";
          table += "<td>" + value.dsc + "</td>";
          table += "<td>" + value.lot + "</td>";
          table += "<td>" + value.exp + "</td>";
          table += "<td>";
          table += "<button id='btn-removeSaleData' data-id='" + key + "' class=\"btn btn-danger btn-xs btn-deleteItem\">";
          table += "<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\">";
          table += "</span></button></td>";
          table += "</tr>";
        console.log(value);
      });

      table += "<tr>";
      table += "<td colspan='3'><strong>" + "Subtotal:" + "</strong></td>";
      table += "<td colspan='5'>" + st + "</td>";
      table += "</tr>";
      table += "<tr>";
      table += "<td colspan='3'><strong>" + "Discount:" + "</strong></td>";
      table += "<td colspan='5'>" + d + "</td>";
      table += "</tr>";
      table += "<tr>";
      table += "<td colspan='3'><strong>" + "Total:" + "</strong></td>";
      table += "<td colspan='5'>" + parseFloat(st - d) + "</td>";
      table += "</tr>";

      $('#invoice-table').html(table);
    }

  }, 'json');

  /*End Load Cart*/

  $('#input-pcode').on('input', function(){
    var prod_code = decodeHtml($(this).val());
    $.post('item/postitem', {getall:true, pcode:prod_code},function(data){
      if(data != null){
        $('#item-code').text(decodeHtml(data.p_code));
        $('#item-lotno').text(data.lotNo);
        $('#item-name').text(data.name);
        $('#item-desc').text(data.desc);
        $('#item-exp').text(data.expiry);
          
        //$('#item-qtyr').text(data.retail);
        $('#item-qtyr').text(data.sum[0].retail);
        //$('#item-qtyr').text(data.packages);
        $('#item-qtyp').text(data.sum[0].packages);
        
        $('#item-pricer').text(data.price_retail);
        $('#item-pricep').text(data.price_package);

        $('#input-exp').val(data.expiry);
        $('#input-lot').val(data.lotNo);

        $('#price-ret').val(data.price_retail);
        $('#price-pkg').val(data.price_package);

        $("#input-stype").trigger('change');
      }
    }, "json");
  });

  $('#input-pcode').on('typeahead:selected', function(){
    $(this).val().trim();
    $(this).trigger('input');
  })

  $('#input-pcode').on('focus', function(){
    $(this).val("");
  })

  $('#input-pcode').on('blur', function(){
    $(this).val(decodeHtml($(this).val()));
  }); 

  $("#input-stype").on('change', function(){
    $('#input-qty').attr('max', '0');
    if($(this).val() == 'ret'){
      $('#input-qty').attr('max', $('#item-qtyr').text());
      $('#input-amt').val($('#price-ret').val());
    } else {
      $('#input-qty').attr('max', $('#item-qtyp').text());
      $('#input-amt').val($('#price-pkg').val());
    }
  });

  $("[id^=input-]").on('input', function(){
    $('#qty').text($('#input-qty').val());
    $('#type').text($('#input-stype option:selected').text());
    $('#dsc').text($('#input-dsc').val());

    var amt = $('#input-amt').val();
    var qty = $('#input-qty').val();
    var dsc = $('#input-dsc').val();

    var total = dsc.indexOf("%") < 0 ? (amt * qty) - dsc : (amt * qty) - ((amt * qty) * dsc.replace("%", "") / 100);
    $('#input-totAmt').val(total);

    var totDsc = dsc.indexOf("%") < 0 ? dsc : ((amt * qty) * dsc.replace("%", "") / 100);
    $('#input-totDsc').val(totDsc);

    $('#tot').text("P " + total);
  });

  $("#input-dsc").on('keyup', function(e){
    var thisField = $("#input-dsc");
    var origVal = thisField.val();

    console.log(origVal);
    console.log(e.keyCode);

    if((e.keyCode < 48 | e.keyCode > 57) && e.keyCode != 190 && e.keyCode != 16 && e.keyCode != 8) {
      origVal = origVal.substring(0, origVal.length - 1);
      thisField.val(origVal);
    }

  });

  $('#input-invoice').on('click', function(){
    $(this).val("");
  });

  $('#form-sale').on('submit', function(e){
      var thisForm = $('#form-sale');
      if(e.originalEvent !== undefined){
          e.preventDefault();
          bootbox.confirm("Are you sure you want to sell this item?", function(resp) {
            if(resp) {
              var formData = $(thisForm).serializeObject();
              
              var currentQty = $('#input-qty').attr('max');
              var newQty = currentQty - formData.qty
              $('#input-qty').attr('max', newQty);
              $.post('sale/new', formData, function(data){
                //console.log(data);
                $('#input-pcode').click();
                $('#input-invoice').val(data);

                var table="<tr><th>Code</th><th>Qty</th><th>Amt</th><th>Type</th><th>Dsc</th><th>Lot</th><th>Exp</th><th>Remove</th></tr>";
                var st = 0;
                var d = 0;

                $.each(data, function(key, value){
                    st += parseFloat(value.amt);
                    d += parseFloat(value.dsc);
                    table += "<tr>";
                    table += "<td>" + value.code + "</td>";
                    table += "<td>" + value.qty + "</td>";
                    table += "<td>" + value.amt + "</td>";
                    table += "<td>" + value.type + "</td>";
                    table += "<td>" + value.dsc + "</td>";
                    table += "<td>" + value.lot + "</td>";
                    table += "<td>" + value.exp + "</td>";
                    table += "<td>";
                    table += "<button id='btn-removeSaleData' data-id='" + key + "' class=\"btn btn-danger btn-xs btn-deleteItem\">";
                    table += "<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\">";
                    table += "</span></button></td>";
                    table += "</tr>";
                  console.log(value);
                });

                table += "<tr>";
                table += "<td colspan='3'><strong>" + "Subtotal:" + "</strong></td>";
                table += "<td colspan='5'>" + st + "</td>";
                table += "</tr>";
                table += "<tr>";
                table += "<td colspan='3'><strong>" + "Discount:" + "</strong></td>";
                table += "<td colspan='5'>" + d + "</td>";
                table += "</tr>";
                table += "<tr>";
                table += "<td colspan='3'><strong>" + "Total:" + "</strong></td>";
                table += "<td colspan='5'>" + parseFloat(st - d) + "</td>";
                table += "</tr>";

                $('#invoice-table').html(table);

                /*
                var invoiceLink = 'invoice/' + data;
                $('#invoice-display').load( invoiceLink + ' #invoice-data');
                $('#invoice-link').attr('href', invoiceLink);
                
                $('#input-pcode').trigger('click');*/
              }, 'json');
            }
          });
      }
  });

  $(document).on('click', '#btn-removeSaleData', function(e){
    var thisindex = $(this).attr('data-id');
    console.log('Deleting ' + thisindex);
    $.post('sale/remove', {i:thisindex},function(data){
      var table="<tr><th>Code</th><th>Qty</th><th>Amt</th><th>Type</th><th>Dsc</th><th>Lot</th><th>Exp</th></tr>";
      //console.log(data);

      $.each(data, function(key, value){
          table += "<tr>";
          table += "<td>" + value.code + "</td>";
          table += "<td>" + value.qty + "</td>";
          table += "<td>" + value.amt + "</td>";
          table += "<td>" + value.type + "</td>";
          table += "<td>" + value.dsc + "</td>";
          table += "<td>" + value.lot + "</td>";
          table += "<td>" + value.exp + "</td>";
          table += "<td>";
          table += "<button id='btn-removeSaleData' data-id='" + key + "' class=\"btn btn-danger btn-xs btn-deleteItem\">";
          table += "<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\">";
          table += "</span></button></td>";
          table += "</tr>";
        console.log(value);
      });

      $('#invoice-table').html(table);
    }, 'json');
  });

  $('#btn-saleConfirm').on('click', function(e){
    bootbox.confirm("Confirm the sale?", function(resp) {
      if(resp){
        $.post('sale/confirm', function(data){
          console.log(data);
          var invoiceLink = 'invoice/' + data;
          var win = window.open(invoiceLink, '_blank');
          win.focus();
        }, 'json');
      }
    });
  });

});