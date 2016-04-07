$gn.ready(function(checkout) {
var callback = function(error, response) {
  if(error) {
    console.error(error);
  } else {
    var post = Object();
    post.payment_token = response.data.payment_token;
    // --- Seu token deve estar armazenado, provavelmente em response.data.payment_token
    alert(response.data.payment_token);
    // --- 
    $.ajax({
        type: "POST",
        data: post,
        url: "charge/credit_card"
      }).done(function(data) {
        console.log(data);
      });
  }
};


$("‪#‎pay‬[payment='card']").click(function(){
  jQuery('pre').remove();
  checkout.getPaymentToken({
    brand: 'visa',
    number: '4532425338091497',
    cvv: '123',
    expiration_month: '05',
    expiration_year: '2018'
  }, callback);
});