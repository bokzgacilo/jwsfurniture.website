const currentURL = new URL(location.href);
const inputParams = new URLSearchParams(currentURL.search);
var params = new URL(document.location).searchParams;

function getAllProducts(){
  $.ajax({
    type: 'get',
    url: 'api/getAllProducts.php',
    beforeSend: () => {
      $('.product-container').html("<span class='mt-4 loader'></span>")
    },
    success: (response) => {
      if(response == 0){
        $('.product-container').load('component/product-not-found.html')
      }else {
        $('.product-container').html(`<div id='product-list'>${response}</div>`)
      }
    }
  })
}

function getAllProductsByCategory(q, category){
  $.ajax({
    type: 'get',
    url: 'api/getAllProductsByCategory.php',
    data: {
      q: q,
      category: category
    },
    beforeSend: () => {
      $('.product-container').html("<span class='mt-4 loader'></span>")
    },
    success: (response) => {
      if(response == 0){
        $('.product-container').load('component/product-not-found.html')
      }else {
        $('.product-container').html(`<div id='product-list'>${response}</div>`)
      }
    }
  })
}

function getAllCategories() {
  $.ajax({
    type: 'get',
    url: 'api/getCategories.php',
    beforeSend: () => {
      $('.category-list').html("<span class='loader'></span>")
    },
    success: (response) => {
      $('.category-list').html(response)

      if(params.get('category') !== null){
        $(`a[name='${params.get('category')}']`).addClass('category-selected');
      }
    }
  })
}

function addToCart(id){
  var quantity = $(`#item${id}`).val();
  
  $.ajax({
    type: 'post',
    url: 'api/addtocart.php',
    data: {
      quantity: quantity,
      id: id
    },
    success: (response) => {
      console.log(response)
    }
  })
}

$('.quickview-closer').on('click', function(){
  $('.quickview').animate({
    top: '100vh'
  }, 500, () => {
    $('.quickview').css({
      'display':'none'
    })
  })
})

function quickView(id){
  location.href = `product.php?id=${id}`;
}

$(document).ready(function(){
    
    if(params.get('category') !== null && params.get('q') === null){
        console.log('has category')
         $.ajax({
            type: 'get',
            url: 'api/getAllProductsByCategory.php',
            data: {
              category: params.get('category'),
              q: ''
            },
            beforeSend: () => {
              $('.product-container').html("<span class='mt-4 loader'></span>")
            },
            success: (response) => {
              if(response == 0){
                $('.product-container').load('component/product-not-found.html')
              }else {
                $('.product-container').html(`<div id='product-list'>${response}</div>`)
              }
            }
         })
    }else if(params.get('q') !== null && params.get('category') !== null) {
        console.log('has q and category')
        $.ajax({
          type: 'get',
          url: 'api/getAllProductsByCategory.php',
          data: {
            q: params.get('q'),
            category: params.get('category')
          },
          beforeSend: () => {
            $('.product-container').html("<span class='mt-4 loader'></span>")
          },
          success: (response) => {
            if(response == 0){
              $('.product-container').load('component/product-not-found.html')
            }else {
              $('.product-container').html(`<div id='product-list'>${response}</div>`)
            }
          }
        })
    }else if(params.get('q') !== null && params.get('category') === null) {
        console.log('has q')
        $.ajax({
          type: 'get',
          url: 'api/search.php',
          data: {
            q: params.get('q')
          },
          beforeSend: () => {
            $('.product-container').html("<span class='mt-4 loader'></span>")
          },
          success: (response) => {
            if(response == 0){
              $('.product-container').load('component/product-not-found.html')
            }else {
              $('.product-container').html(`<div id='product-list'>${response}</div>`)
            }
          }
        })
    }else {
        getAllProducts();
    }
    
    getAllCategories();
    
})  

$(document).on('click', '.category-list > a', function(){
  $('.category-list > a').removeClass('category-selected');
  $(this).addClass('category-selected');

  var category = $(this).attr('name');

  inputParams.set('category', category)

  const path = window.location.href.split('?')[0];
  const newURL = `${path}?${inputParams}`;

  history.pushState({}, '', newURL);
  
  if(params.get('q') !== null){
      getAllProductsByCategory(params.get('q'), category);
  }else {
      getAllProductsByCategory('', category);
  }
})