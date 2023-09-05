<?php
session_start();

if(!(isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0)) {
  die("<html><body><script>alert('Your cart is empty. Place items first.'); window.location.replace('/shop.php');</script></body></html>");
}

if(!isset($_SESSION["email_address"])) {
  die("<html><body><script>alert('Please log-in first before checking out.'); window.location.replace('/login.php');</script></body></html>");
}

$user_logged_in_email = $_SESSION["email_address"];

// Create connection to database
require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
$conn = get_connection();

$sql = "SELECT first_name, last_name, email_address, phone_number FROM customer WHERE email_address = '$user_logged_in_email'";
$result = $conn->query($sql);
$customer_row = $result->fetch_assoc();


  // check first if user is logged in
  // if not, redirect to login.php

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
              <h1 class="h2">Checkout</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Checkout</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div id="content">
        <div class="container">
          <div class="row justify-content-center">
            <div id="checkout" class="col-lg-9">
              <div class="box border-bottom-0">
                <h2><i class="fa fa-shopping-cart"></i> Order Summary</h2>
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
                              <?= $cart_item -> quantity ?>
                            </td>
                            <td>Php<?= number_format($cart_item -> final_unit_price, 2) ?></td>
                            <td>Php<?= number_format($cart_item -> subtotal, 2) ?></td>
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
                        <tr>
                          <td colspan="4">Shipping and Handling</td>
                          <td colspan="2">Php<?= number_format(100.0, 2) ?></td>
                        </tr>
                        <tr>
                          <th colspan="4">Total</th>
                          <th colspan="2">Php<?= number_format($total + 100, 2) ?></th>
                        </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              <div class="box mt-0">
                <form method="POST" action="save_order.php">
                  <h2><i class="fa fa-map-marker"></i> Address</h2>
                  <div class="content">

                    <div class="form-group">
                      <label>Full name</label>
                      <input type="text" readonly class="form-control" value="<?= $customer_row['first_name']." ".$customer_row['last_name'] ?>">
                    </div>

                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="phone">Telephone</label>
                          <input type="text" readonly class="form-control" value="<?= $customer_row['phone_number'] ?>">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="text" readonly class="form-control" value="<?= $customer_row['email_address'] ?>">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="phone">Billing Address</label>
                      <textarea class="form-control" name="billing_address"></textarea>
                    </div>

                    <div class="form-group">
                      <label for="email">Shipping Address</label>
                      <textarea class="form-control" name="shipping_address"></textarea>
                    </div>

                    <div class="form-group">
                      <label for="email">Additional Notes</label>
                      <input name="additional_notes" type="text" class="form-control">
                    </div>

                  </div>
                  <div class="box-footer d-flex flex-wrap align-items-center justify-content-between">
                    <div class="left-col"><a href="shop-basket.html" class="btn btn-secondary mt-0"><i class="fa fa-chevron-left"></i>Back to basket</a></div>
                    <div class="right-col">
                      <button type="submit" class="btn btn-template-main">Place Order<i class="fa fa-chevron-right"></i></button>
                    </div>
                  </div>
                </form>
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
