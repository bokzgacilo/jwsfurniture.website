<script>
  $('#productDescription').richText({
    fonts: false,
    table: false,
    underline: false,
    urls: false,
    leftAlign: false,
    centerAlign: false,
    rightAlign: false,
    justify: false,
    videoEmbed: false,
    imageUpload: false,
    fileUpload: false,
    removeStyles: false
  });
</script>

<style>
  .item-list {
    display: grid;
    grid-template-columns: 19% 19% 19% 19% 19%;
    column-gap: 10px;
    row-gap: 10px;
  }

  #addProduct {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .fr-second-toolbar {
    display: none;
  }

  .fr-box.fr-basic .fr-wrapper {
    border-bottom-color: #CCCCCC;
  }
</style>



<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="productViewCanvas" aria-labelledby="productViewCanvasLabel">
  <div class="offcanvas-header">
    <p class="is-size-4 has-text-weight-bold" id="productViewCanvasLabel">Add New Product</p>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="addProduct" method="post" action="api/addproduct.php" enctype="multipart/form-data">
      <input required id="productName" name="name" type="text" class="form-control" placeholder="Name"/>
      <input required id="productPrice" name="price" type="number" class="form-control" placeholder="Pricing"/>
      <textarea required name="description" id="productDescription"></textarea>
      <select required name="category" id="productCategory" class="form-control">
        <option>Chair</option>
        <option>Table</option>
        <option>Door</option>
        <option>Window</option>
        <option>Cabinet</option>
      </select>
      <h6>Upload image for the product</h6>
      <input accept="image/*" name="image" required id="productImage" class="form-control" type="file" />
      <button type="submit" class="button is-success">Add Product</button>
    </form>
  </div>
</div>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="product_view" aria-labelledby="productViewCanvasLabel">
  <div class="offcanvas-header">
    <p class="is-size-4 has-text-weight-bold" id="productViewCanvasLabel">Product Offcanvas</p>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    
  </div>
</div>

<p class="is-size-4 has-text-weight-bold">Products</p>

<div>
  <button class="button is-link" data-bs-toggle="offcanvas" data-bs-target="#productViewCanvas  ">Add Product</button>
</div>
<div class="item-list">

</div>

<script>
  function getAllProducts(){
    $.ajax({
      type: 'get',
      url: 'api/getAllProducts.php',
      success: (response) => {
        $('.item-list').html(response)
      }
    })
  }

  function deactivate(product_id){
    $.ajax({
      type: 'post',
      url: 'api/deactivateProduct.php',
      data: {
        product_id: product_id
      },
      success: response => {
        if(response == 1){
          location.reload();
        }
      }
    })
  }

  function activate(product_id){
    $.ajax({
      type: 'post',
      url: 'api/activateProduct.php',
      data: {
        product_id: product_id
      },
      success: response => {
        if(response == 1){
          location.reload();
        }
      }
    })
  }

  $(document).ready(function(){
    if(location.search != ""){
      let params = new URL(document.location).searchParams;
      var productID = params.get('id');
      viewImage(productID)
    }

    getAllProducts()
  })
</script>