$(document).ready(function() {

    $('#btn__search').click(function(){
      $('#home').removeClass('active');
      $('#data__container').addClass('active');
    });
    $('#data__container #arrow__back').click(function(){
      $('#home').addClass('active');
      $('#data__container').removeClass('active');
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
    $(this).addClass('active');
    $('#button__goal').removeClass('active');
  });
  $('#button__goal').click(function(){
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

});
