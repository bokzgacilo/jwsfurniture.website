var quantity = parseInt($("input[name='quantity']").val());
var max_value = $("input[name='quantity']").attr('max');
var product_id = $("input[name='product_id']").val();

function buttonEnabler(){
  if(quantity == max_value){
    $('.increase-button').attr('disabled', 'disabled');
  }else {
    $('.increase-button').removeAttr('disabled');
  }

  if(quantity > 0){
    $('.decrease-button').removeAttr('disabled');
  }else {
    $('.decrease-button').attr('disabled', 'disabled');
  }

  console.log(quantity)
}


$(document).ready(function(){
  buttonEnabler();
})

function increaseInputValue() {
  quantity = parseInt(quantity + 1);

  buttonEnabler();
  $("input[name='quantity']").val(quantity)
}

function decreaseInputValue() {
  quantity = quantity - 1;


  buttonEnabler();
  $("input[name='quantity']").val(quantity)

}


$(document).on('submit', '#orderForm', function(event){
  event.preventDefault();

  $.ajax({
    type: 'post',
    url: 'api/addtocart.php',
    data: {
      quantity: quantity,
      product_id: product_id
    },
    success: (response) => {
      console.log(response)

      Swal.fire({
        title: 'Cart Updated!',
        text: "Product was added to your cart",
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Go to my cart'
      }).then((result) => {
        if (result.isConfirmed) {
          location.href = "cart.php"
        }
      })
    }
  })

})

