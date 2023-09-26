$(document).ready(function(){
  $('.modal-closer').on('click', function(){
    $(this).parent().parent().parent().css({
      'display' : 'none'
    })
  })
})