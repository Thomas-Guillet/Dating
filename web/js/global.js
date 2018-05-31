$(document).ready(function() {

  var getDataSelfConfidence = function() {
    if(xhr){
      xhr.abort()
    }

    $('#button__render').addClass('active');
    $('#data_render').addClass('hide');

    if($('#women').is(':checked') && $('#men').is(':checked')){
      var iGender = 3;
    }else if($('#women').is(':checked')){
      var iGender = 1;
    }else if($('#men').is(':checked')){
      var iGender = 2;
    }else{
      var iGender = 3;
    }

    var iMinAge = 60 - $( "#slider-range" ).slider( "values", 1 );
    var iMaxAge = 60 - $( "#slider-range" ).slider( "values", 0 );

    var dataObject = {
      iGender: iGender,
      iMaxAge: iMaxAge,
      iMinAge: iMinAge,
    };
    var xhr = $.ajax({
      method: 'post',
      url: $('#ajax').val()+'ajax_self_confidence.php',
      data: dataObject,
      success: function(data) {
        var promises = [];
        var aData = JSON.parse(data);

        setTimeout(function() {
          $.each(aData, function(key, value) {
            $('#column-'+key).html(value);
          })
          $('#button__render').removeClass('active');
          $('#data_render').removeClass('hide');
        }, 1000);
      }
    });
  }

  $('#loader').addClass('hide');
  setTimeout(function() {
    $('#loader').css('display', 'none');
  }, 1000);

  $('#btn__search').click(function(){
    $('#home').removeClass('active');
    $('#data__container').addClass('active');
  });
  $('#data__container #arrow__back').click(function(){
    $('#home').addClass('active');
    $('#data__container').removeClass('active');
    $('#diagram__goal').removeClass('hide');
    $('#diagram__self__confidence').removeClass('show');
    $('#text__self__confidence').css('transition', '.2s');
    $('#text__self__confidence').removeClass('show');
    $('#label__self__confidence').removeClass('show');
    $('#button__self-confidence').removeClass('active');
    $('#button__goal').addClass('active');
  });
  $('#learn__more #arrow__back').click(function(){
    $('#home').addClass('active');
    $('#learn__more').removeClass('active');
  });
  $('#btn__learn__more').click(function(){
    $('#home').removeClass('active');
    $('#learn__more').addClass('active');
  });

  $('#button__self-confidence').click(function(){
    if(!$(this).hasClass('active')){
      $('#diagram__goal').addClass('hide');
      $('#diagram__self__confidence').addClass('show');
      $('#text__self__confidence').css('transition', '1s');
      $('#text__self__confidence').addClass('show');
      $('#label__self__confidence').addClass('show');
    }
    $(this).addClass('active');
    $('#button__goal').removeClass('active');
  });
  $('#button__goal').click(function(){
    if(!$(this).hasClass('active')){
      $('#diagram__goal').removeClass('hide');
      $('#diagram__self__confidence').removeClass('show');
      $('#text__self__confidence').removeClass('show');
      $('#label__self__confidence').removeClass('show');
    }
    $(this).addClass('active');
    $('#button__self-confidence').removeClass('active');
  });

  $( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 20,
      max: 40,
      values: [ 25, 32 ],
      orientation: "vertical",
      slide: function( event, ui ) {
        $( "#amount" ).val( (40 - (ui.values[ 0 ] )) + " - " + (60 - (ui.values[ 1 ] )));
        $($( ".ui-slider-handle.ui-corner-all.ui-state-default" )[0]).html('<span>'+(60 - (ui.values[ 0 ] ))+'</span>');
        $($( ".ui-slider-handle.ui-corner-all.ui-state-default" )[1]).html('<span>'+(60 - (ui.values[ 1 ] ))+'</span>');
        var min = 60 - ui.values[ 1 ];
        var max = 60 - ui.values[ 0 ];
        if(min < 22){
          $('#slider__min').addClass('hidden');
        }else{
          $('#slider__min').removeClass('hidden');
        }
        if(max > 38){
          $('#slider__max').addClass('hidden');
        }else{
          $('#slider__max').removeClass('hidden');
        }
      }
    });
    $( "#amount" ).val( (60 -$( "#slider-range" ).slider( "values", 0 )) +
    " - " + (60 - $( "#slider-range" ).slider( "values", 1 )) );
    $($( ".ui-slider-handle.ui-corner-all.ui-state-default" )[0]).html('<span>'+(60 - $( "#slider-range" ).slider( "values", 0 ))+'</span>');
    $($( ".ui-slider-handle.ui-corner-all.ui-state-default" )[1]).html('<span>'+(60 - $( "#slider-range" ).slider( "values", 1 ))+'</span>');
  } );


  $('#button__render').click(getDataSelfConfidence);
  $('#button__render').click(generatePieChart);

  var radius = 150;
  // var perc = document.querySelectorAll('.perc');
  //
  // for (i = 0; i < perc.length; ++i) {
  //   perc[i]
  //   new CircleType(  perc[i]).radius(radius);
  // }
  // new CircleType(document.getElementById('pie-label-1')).radius(radius);
  // new CircleType(document.getElementById('pie-label-2')).radius(radius);
  // new CircleType(document.getElementById('pie-label-3')).radius(radius);
  // new CircleType(document.getElementById('pie-label-4')).radius(radius);
  // new CircleType(document.getElementById('pie-label-5')).radius(radius);
  // new CircleType(document.getElementById('pie-label-6')).radius(radius);


})
