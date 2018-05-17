$(document).ready(function() {

  $('#btn__search').click(function(){
    $('#home').removeClass('active');
    $('#data__container').addClass('active');
  });

  $('#button__self-confidence').click(function(){
    $(this).addClass('active');
    $('#button__goal').removeClass('active');
  });
  $('#button__goal').click(function(){
    $(this).addClass('active');
    $('#button__self-confidence').removeClass('active');
  });

});
