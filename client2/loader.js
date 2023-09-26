$(window).scroll(function() {
  if ($(this).scrollTop() > 0){  
    $('header').addClass("bordered");
  }
  else{
    $('header').removeClass("bordered");
  }
});

$(document).ready(function(){

  // Load Header Component to Header Container across different pages
  $('#header-container').load("component/header.php")
  $('#header-container-product').load("../component/header.php")
  
})