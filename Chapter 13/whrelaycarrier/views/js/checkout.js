$(function(){

  if($('#delivery_option_'+id_carrier).prop('checked')){
    $('#whrelaycarrier_points').css('display', 'flex');
    if(id_relay>0){
      $('.delivery-options-list button').show();
    }else{
      $('.delivery-options-list button').hide();
    }
  }

  $('#delivery_option_'+id_carrier).click(function(){
    if($(this).prop('checked')){
      $('#whrelaycarrier_points').css('display', 'flex');
      if(id_relay>0){
        $('.delivery-options-list button').show();
      }else{
        $('.delivery-options-list button').hide();
      }
    }
  });

  $('.delivery-option').has('#delivery_option_'+id_carrier).click(function(){
      $('#whrelaycarrier_points').css('display', 'flex');
      if(id_relay>0){
        $('.delivery-options-list button').show();
      }else{
        $('.delivery-options-list button').hide();
      }
  });

  $('.delivery-option input').not('#delivery_option_'+id_carrier).click(function(){
      $('.delivery-options-list button').show();
      $.ajax({
        method: "POST",
        url: ajaxUrl,
        data: { relay: 0, cart: id_cart },
        dataType: "json"
      })
      .done(function( jsonResponse ) {
        if(jsonResponse.return_code == 'OK'){
          $('#whrelaycarrier_points>div>ul>li').removeClass('active');
        }
      });
  });

  $('#whrelaycarrier_points>div>span, #whrelaycarrier_submit').click(function(){
    $('#whrelaycarrier_points').hide();
    if(id_relay>0){
      $('.delivery-options-list button').show();
    }else{
      $('.delivery-options-list button').hide();
    }
  });

  $('#whrelaycarrier_points>div>ul>li').click(function(){
    $('#whrelaycarrier_points>div>ul>li').removeClass('active');
    id_relay = $(this).data('id-relay');
    var itemSelected = $(this);
    if(id_relay!=0){
      $.ajax({
        method: "POST",
        url: ajaxUrl,
        data: { relay: id_relay, cart: id_cart },
        dataType: "json"
      })
      .done(function( jsonResponse ) {
        if(jsonResponse.return_code == 'OK'){
          itemSelected.addClass('active');
        }
      });
    }
  });
});
