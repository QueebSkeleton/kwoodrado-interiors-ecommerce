<?php
  session_start();
  
  // Create connection to database
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();
  
  class ShoppingCartItem {
    public $product_id;
    public $product_name;
    public $product_image_name;
    public $final_unit_price;
    public $units_in_stock;
    public $quantity;
    public $is_taxable;
    public $subtotal;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kwoodrado Interiors | Welcome</title>
    <?php include('include/head-tags.php'); ?>
  </head>
  <body>
    <div id="all">
      <?php
        
        include("include/navbar.php");
      ?>

      <div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Shopping Cart</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Shopping Cart</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="content">
        <div class="container">
          <div class="row bar">
            <div class="col-lg-12">
              <p class="text-muted lead">You currently have <?= isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : "0" ?> item(s) in your cart.</p>
            </div>
            <div id="basket" class="col-lg-9">
              <div class="box mt-0 pb-0 no-horizontal-padding">
                <form method="post" action="update-cart.php">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th colspan="2">Product</th>
                          <th>Quantity</th>
                          <th>Unit price</th>
                          <th colspan="2">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $total = 0; ?>
                        <?php if(isset($_SESSION["cart"])): ?>
                        <?php foreach($_SESSION["cart"] as $cart_item): ?>
                        <tr>
                          <td><a href="#"><img src="<?= is_null($cart_item -> product_image_name) ? "/img/empty-image.png" : "/product-images/".$cart_item -> product_image_name ?>" alt="<?= $cart_item -> product_name ?>" class="img-fluid"></a></td>
                          <td><a href="#"><?= $cart_item -> product_name ?></a></td>
                          <td>
                            <input type="hidden" name="productid[]" value="<?= $cart_item -> product_id ?>">
                            <input type="number" name="quantity[]" min="1" max="<?= $cart_item -> units_in_stock ?>" value="<?= $cart_item -> quantity ?>" class="form-control">
                          </td>
                          <td>Php<?= number_format($cart_item -> final_unit_price, 2) ?></td>
                          <td>Php<?= number_format($cart_item -> subtotal, 2) ?></td>
                          <td><a href="/remove-from-cart.php?id=<?= $cart_item -> product_id ?>"><i class="fa fa-trash-o"></i></a></td>
                        </tr>
                        <?php $total += $cart_item -> subtotal; ?>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                          <td colspan="6">No items to display</td>
                        </tr>
                        <?php endif; ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th colspan="4">Total</th>
                          <th colspan="2">Php<?= number_format($total, 2) ?></th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <div class="box-footer d-flex justify-content-between align-items-center">
                    <div class="left-col"><a href="/shop.php" class="btn btn-secondary mt-0"><i class="fa fa-chevron-left"></i> Continue shopping</a></div>
                    <div class="right-col">
                      <button type="submit" class="btn btn-secondary"><i class="fa fa-refresh"></i> Update cart</button>
                      <a href="place-order.php" class="btn btn-template-outlined">Proceed to checkout <i class="fa fa-chevron-right"></i></a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-lg-3">
              <div id="order-summary" class="box mt-0 mb-4 p-0">
                <div class="box-header mt-0">
                  <h3>Order summary</h3>
                </div>
                <p class="text-muted">Shipping and additional costs are calculated based on the values you have entered. Taxes already included.</p>
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>Order subtotal</td>
                        <th>Php<?= number_format($total, 2) ?></th>
                      </tr>
                      <tr>
                        <td>Shipping and handling</td>
                        <th>Php100.00</th>
                      </tr>
                      <tr class="total">
                        <td>Total</td>
                        <th>Php<?= number_format($total + 100, 2) ?></th>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php /* Footer */ include('include/footer.php'); ?>
    </div>

    <?php /* All scripts */ include('include/scripts.php'); ?>

    <?php /* login modal */ if(!isset($_SESSION["email_address"])) include('include/login-register-modals.php'); ?>

  </body>
</html>

<?php
  // Close resources
  mysqli_close($conn);
?>
