function removeFromCart(id){
  $.ajax({
    type: 'post',
    url: 'api/removeItemFromCart.php',
    data: {
      id: id
    },
    success: (response) => {
      console.log(response)

      if(response == 1){
        location.reload()
      }
    }
  })
}

function plusQuantity(id){
  $.ajax({
    type: 'post',
    url: 'api/incrementProduct.php',
    data: {
      id: id
    },
    success: (response) => {
      console.log(response)

      if(response == 1){
        location.reload()
      }
    }
  })
}

function minusQuantity(id){
  $.ajax({
    type: 'post',
    url: 'api/decrementProduct.php',
    data: {
      id: id
    },
    success: (response) => {
      console.log(response)

      if(response == 1){
        location.reload()
      }
    }
  })
}