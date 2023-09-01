<?php
  session_start();

  // If user is not yet logged in, redirect to login page
  if(!isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re not logged in. Redirecting you to login page.'); ".
    "window.location.replace('/login.php');</script></body></html>");
  }
  
  // Create connection to database
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kwoodrado Interiors | My Orders</title>
    <?php include('include/head-tags.php'); ?>
  </head>
  <body>
    <div id="all">
      <?php
        include("include/topbar.php");
        include("include/navbar.php");
      ?>

      <div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">My Orders</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">My Orders</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="content">
        <div class="container">
          <div class="row bar mb-0">
            <div id="customer-orders" class="col-md-9">
              <p class="text-muted lead">If you have any questions, please feel free to <a href="contact.html">contact us</a>, our customer service center is working for you 24/7.</p>
              <div class="box mt-0 mb-lg-0">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $order_result = mysqli_query($conn, "SELECT placed_order.id, placed_order.placed_in, placed_order.status, ".
                        "SUM(placed_order_item.quantity * placed_order_item.final_unit_price) AS total ".
                        "FROM placed_order LEFT JOIN placed_order_item ON placed_order_item.order_id = placed_order.id ".
                        "WHERE placed_order.customer_email_address = '".$_SESSION["email_address"]."' ".
                        "GROUP BY placed_order.id");

                        while($row = mysqli_fetch_assoc($order_result)):
                      ?>
                      <tr>
                        <th># <?= $row["id"] ?></th>
                        <td><?= $row["placed_in"] ?></td>
                        <td>Php<?= number_format($row["total"], 2) ?></td>
                        <td>
                          <?php
                          switch($row["status"]) {
                              case 'NEW':
                                echo "<span class='badge badge-primary'>New</span>";
                                break;
                              case 'PROCESSING':
                                echo "<span class='badge badge-info'>Processing</span>";
                                break;
                              case 'DELIVERED':
                                echo "<span class='badge badge-success'>Received</span>";
                                break;
                              case 'DENIED':
                                echo "<span class='badge badge-warning'>Cancelled</span>";
                                break;
                              case 'CANCELLED':
                                echo "<span class='badge badge-danger'>Danger</span>";
                                break;
                          }
                          ?>
                        </td>
                        <td>
                          <a href="view-my-order.php?id=<?= $row["id"] ?>" class="btn btn-template-outlined btn-sm">View</a>
                          <a href="invoice.php?id=<?= $row["id"] ?>" class="btn btn-template-outlined btn-sm">Invoice</a>
                        </td>
                      </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-3 mt-4 mt-md-0">
              <!-- CUSTOMER MENU -->
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Customer section</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">
                    <li class="nav-item"><a href="my-orders.php" class="nav-link active"><i class="fa fa-list"></i> My orders</a></li>
                    <li class="nav-item"><a href="my-profile.php" class="nav-link"><i class="fa fa-user"></i> My account</a></li>
                    <li class="nav-item"><a href="user_logout.php" class="nav-link"><i class="fa fa-sign-out"></i> Logout</a></li>
                  </ul>
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
