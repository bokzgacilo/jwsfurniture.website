function previewImage(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#avatar-preview').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$(document).on('change', "input[name='avatar_input']", function() {
  previewImage(this);
  $('#change-avatar-button').removeAttr('disabled');
});

$(document).on('click', '.order-id', function(){
  let order_id = $(this).attr('id');

  $.ajax({
    type: 'get',
    url: 'api/getOrderDetail.php',
    data: {
     id: order_id 
    },
    beforeSend: () => {
      
    },
    success: response => {
      $('#order-viewer').addClass('is-active');
      $('#order-body').html(response);
    }
  })
})

$(document).on('submit', '#avatarForm', function(event){
  event.preventDefault();
  
  var formData = new FormData($(this)[0]);

  $.ajax({
    type: 'post',
    url: 'api/editPicture.php',
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: (response) => {
      console.log(response)
      if(response == 1){
        location.reload();
      }
    }
  })
})


$(document).on('submit', '#addressForm', function(event){
  event.preventDefault();

  var serializedData = $(this).serialize();

  $.ajax({
    type: 'post',
    url: 'api/editAddress.php',
    data: serializedData,
    success: (response) => {
      if(response == 1){
        location.reload();
      }

      if(response == 3){
        Swal.fire(
          'Something went wrong',
          'The system detected that you want to use email that already registered with another user, please contact the Administrator or <b>use an alternative email</b>.',
          'error'
        )
      }
    }
  })
})

$(document).on('click', '.view-order-button', function(){
  console.log($(this).attr('id'))
  orderID = $(this).attr('id');

  $.ajax({
    type: 'get',
    url: 'api/getOrderDetail.php',
    data: {
      id: orderID
    },
    success: (response) => {
      $('.modal-card-title').text(`Order: ${orderID}`)
      $('.modal-card-body').html(response)
    }
  })
})