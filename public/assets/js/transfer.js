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
    $.get('/pcodes', function(data){
        $.each(data, function( key, value ) {
            codes[key] = value;
            //console.log( key + ": " + value );
        });
    }, "json");

  console.log(codes);

    $('#input-pcode').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'codes',
        displayKey: 'value',
        source: substringMatcher(codes)
    });

    $('#input-pcode').on('typeahead:selected', function(){
        $(this).val(decodeHtml($(this).val()));
        $(this).val().trim();
        var prod_code = $(this).val();
        $.post('item/postitem', {pcode:prod_code}, function(data){
            if(data != null){                
                $('#item-qtyp').val(data.packages);
                $('#item-qtyr').val(data.retail);
                $('#input-lot').val(data.lotNo);
                $('#input-exp').val(data.expiry);
            }
        }, "json");

        $('.input-ioo').attr('disabled', false);
        $('#input-qty').attr('disabled', false);
        //$('#input-type').attr('disabled', false);
    });

    $('#input-pcode').on('blur', function(){
        $(this).val(decodeHtml($(this).val()));
    });

    $('.input-ioo').on('click', function(e){
        var type = $('#input-type').val();
        var ioo  = $(this).val();

        if(ioo == 'Out') {
            $('#newGroup').addClass('hidden');
            $('#input-from').attr('disabled', true);
            $('#input-to').removeAttr('disabled');
            if(type == 'pkg'){
                qmax = $('#item-qtyp').val();
                $('#input-qty').attr('max', qmax);
            } else {
                qmax = $('#item-qtyr').val();
                $('#input-qty').attr('max', qmax);
            }
        } else {
            //in
            $('#newGroup').removeClass('hidden');
            $('#input-to').attr('disabled', true);
            $('#input-from').removeAttr('disabled');
            $('#input-qty').removeAttr('max');
        }
    });

    $('#form-transfer').on('submit', function(e){
        var thisForm = $('#form-transfer');
        if(e.originalEvent !== undefined){
            e.preventDefault();
            bootbox.confirm("Are you sure you want to transfer this item?", function(resp) {
                if(resp) {
                    $('#input-to').removeAttr('disabled');
                    $('#input-from').removeAttr('disabled');
                    thisForm.submit();
                }
            });
        }
    });

    $('#chkNew').on('click', function(e){
        if($(this).is(':checked')){
            $('#lotGroup').removeClass('hidden');
            $('#expGroup').removeClass('hidden');
        } else {
            $('#lotGroup').addClass('hidden');
            $('#expGroup').addClass('hidden');
        }
    });

    $('#input-invoice').on('click', function(){
        $(this).val("");
      });
});