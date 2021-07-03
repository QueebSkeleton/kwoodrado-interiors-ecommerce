<?php
  require_once("dependencies/fpdf/fpdf.php");

  // Only allow get requests
  if($_SERVER["REQUEST_METHOD"] != "GET") {
      http_response_code(400);
      die("Only GET requests are allowed.");
  }

  // Check if order id request parameter exists and is valid
  if(!(isset($_GET["id"]) && is_numeric($_GET["id"]))) {
    http_response_code(400);
    die("Error: No valid order id was specified in the request parameters.");
  }

  class PlacedOrderItem {
    public $product_name;
    public $quantity;
    public $final_unit_price;
    public $is_taxable;
    public $subtotal;
  }

  class PlacedOrder {
    public $id;
    public $customer_name;
    public $customer_phone_number;
    public $customer_email_address;
    public $placed_in;
    public $billing_address;
    public $shipping_address;
    public $additional_notes;
    public $status;
    public $items;
    public $sale_without_tax;
    public $tax;
    public $total;
  }

  // Placeholder for fetched order
  $placed_order = new PlacedOrder();

  // Parse configuration file
  $config = parse_ini_file("../config.ini");

  // Create connection to database
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Fetch order by id
  $order_result = mysqli_query($conn, "SELECT customer.first_name, customer.last_name, customer.phone_number,".
    " customer.email_address, placed_order.placed_in, placed_order.billing_address, placed_order.shipping_address, placed_order.additional_notes,".
    " placed_order.status, product.name, placed_order_item.quantity, placed_order_item.final_unit_price,".
    " placed_order_item.is_taxable FROM placed_order LEFT JOIN customer ON customer.email_address = placed_order.customer_email_address".
    " LEFT JOIN placed_order_item ON placed_order_item.order_id = placed_order.id".
    " LEFT JOIN product ON product.id = placed_order_item.product_id WHERE placed_order.id = ".$_GET["id"]);

  // Check if there is an order with that id
  // by checking if the number of rows returned is 0
  // If so, don't proceed and return 400
  if(mysqli_num_rows($order_result) == 0) {
    mysqli_close($conn);
    http_response_code(400);
    die("Error: The given order id does not correspond any existing order.");
  }

  // Parse the order from the result set
  // First row always contains order data + 1st order item, so hardcode
  $first_row = mysqli_fetch_assoc($order_result);

  $placed_order -> id = intval($_GET["id"]);
  $placed_order -> customer_name = $first_row["first_name"]." ".$first_row["last_name"];
  $placed_order -> customer_phone_number = $first_row["phone_number"];
  $placed_order -> customer_email_address = $first_row["email_address"];
  $placed_order -> placed_in = $first_row["placed_in"];
  $placed_order -> billing_address = $first_row["billing_address"];
  $placed_order -> shipping_address = $first_row["shipping_address"];
  $placed_order -> additional_notes = $first_row["additional_notes"];
  $placed_order -> status = $first_row["status"];

  // If order item quantity is not null, then there is at least 1 item in this order
  // in that case, initialize the $items variable in $placed_order as an array
  if(!empty($first_row["quantity"])) {
    $first_order_item = new PlacedOrderItem();
    $first_order_item -> product_name = $first_row["name"];
    $first_order_item -> quantity = intval($first_row["quantity"]);
    $first_order_item -> final_unit_price = floatval($first_row["final_unit_price"]);
    $first_order_item -> is_taxable = boolval($first_row["is_taxable"]);
    $first_order_item -> subtotal = $first_order_item -> quantity * $first_order_item -> final_unit_price;
    $placed_order -> items = [$first_order_item];

    // If product is taxable, calculate tax of this product then add to total tax
    // TODO: currently this tax scheme is fixed, please change soon
    if($first_order_item -> is_taxable) {
      $placed_order -> tax += $first_order_item -> final_unit_price * $first_order_item -> quantity * 0.12;
    }

    // Calculate then add subtotal to order total
    $placed_order -> total += $first_order_item -> subtotal;
  }

  // Fetch the remaining order items
  while($row = mysqli_fetch_assoc($order_result)) {
    $order_item = new PlacedOrderItem();
    $order_item -> product_name = $row["name"];
    $order_item -> quantity = intval($row["quantity"]);
    $order_item -> final_unit_price = floatval($row["final_unit_price"]);
    $order_item -> is_taxable = boolval($row["is_taxable"]);
    $order_item -> subtotal = $order_item -> quantity * $order_item -> final_unit_price;
    $placed_order -> items[] = $order_item;

    // If product is taxable, calculate tax of this product then add to total tax
    // TODO: currently this tax scheme is fixed, please change soon
    if($order_item -> is_taxable) {
      $placed_order -> tax += $order_item -> final_unit_price * $order_item -> quantity * 0.12;
    }

    // Calculate then add subtotal to order total
    $placed_order -> total += $order_item -> subtotal;
  }

  // Close the database connection
  mysqli_close($conn);

  // Finalize order data
  // Calculate sales without tax
  $placed_order -> sale_without_tax = $placed_order -> total - $placed_order -> tax;

  //create pdf object
  $pdf = new FPDF('P','mm','A4');
  //add new page
  $pdf->AddPage();
  //set font to arial, bold, 14pt
  $pdf->SetFont('Arial','B',14);

  //Cell(width , height , text , border , end line , [align] )

  $pdf->Cell(130 ,5,'KWOODRADO INTERIORS INC.',0,0);
  $pdf->Cell(59 ,5,'INVOICE',0,1);//end of line

  //set font to arial, regular, 12pt
  $pdf->SetFont('Arial','',12);

  $pdf->Cell(130 ,5,'123 BillHouses Street',0,0);
  $pdf->Cell(59 ,5,'',0,1);//end of line

  $pdf->Cell(130 ,5,'Taguig City, Philippines',0,0);
  $pdf->Cell(25 ,5,'Date',0,0);
  $pdf->Cell(34 ,5, $placed_order -> placed_in,0,1);//end of line

  $pdf->Cell(130 ,5,'Phone [+12345678]',0,0);
  $pdf->Cell(25 ,5,'Invoice #',0,0);
  $pdf->Cell(34 ,5, $placed_order -> id,0,1);//end of line

  $pdf->Cell(130 ,5,'Fax [+12345678]',0,0);

  //make a dummy empty cell as a vertical spacer
  $pdf->Cell(189 ,10,'',0,1);//end of line

  //billing address
  $pdf->Cell(100 ,5,'Bill to',0,1);//end of line

  //add dummy cell at beginning of each line for indentation
  $pdf->Cell(10 ,5,'',0,0);
  $pdf->Cell(90 ,5, $placed_order -> customer_name,0,1);

  $pdf->Cell(10 ,5,'',0,0);
  $pdf->Cell(90 ,5, $placed_order -> customer_email_address,0,1);

  $pdf->Cell(10 ,5,'',0,0);
  $pdf->Cell(90 ,5, 'Billing Address: '.$placed_order -> billing_address,0,1);

  $pdf->Cell(10 ,5,'',0,0);
  $pdf->Cell(90 ,5, 'Shipping Address: '.$placed_order -> shipping_address,0,1);

  //make a dummy empty cell as a vertical spacer
  $pdf->Cell(189 ,10,'',0,1);//end of line

  //invoice contents
  $pdf->SetFont('Arial','B',12);

  $pdf->Cell(70 ,5, 'Product Name',1,0);
  $pdf->Cell(25 ,5, 'Taxable',1,0);
  $pdf->Cell(20 ,5, 'Qty',1,0);
  $pdf->Cell(40 ,5, 'Unit Price',1,0);
  $pdf->Cell(34 ,5, 'Subtotal', 1,1,'R');

  $pdf->SetFont('Arial','',12);

  //Numbers are right-aligned so we give 'R' after new line parameter

  foreach($placed_order -> items as $order_item) {
    $pdf->Cell(70 ,5, $order_item -> product_name,1,0);
    $pdf->Cell(25 ,5, $order_item -> is_taxable ? 'Yes': 'No',1,0);
    $pdf->Cell(20 ,5, $order_item -> quantity,1,0);
    $pdf->Cell(40 ,5, 'Php'.number_format($order_item -> final_unit_price, 2),1,0);
    $pdf->Cell(34 ,5, 'Php'.number_format($order_item -> subtotal, 2),1,1,'R');
  }

  //summary
  $pdf->Cell(90 ,5,'',0,0);
  $pdf->Cell(25 ,5,'Sales',0,0);
  $pdf->Cell(10 ,5,'Php',1,0);
  $pdf->Cell(64 ,5,'Php'.number_format($placed_order -> sale_without_tax, 2),1,1,'R');//end of line

  $pdf->Cell(90 ,5,'',0,0);
  $pdf->Cell(25 ,5,'Tax',0,0);
  $pdf->Cell(10 ,5,'Php',1,0);
  $pdf->Cell(64 ,5,'Php'.number_format($placed_order -> tax, 2),1,1,'R');//end of line

  $pdf->Cell(90 ,5,'',0,0);
  $pdf->Cell(25 ,5,'Tax Rate',0,0);
  $pdf->Cell(10 ,5,'%',1,0);
  $pdf->Cell(64 ,5,'12%',1,1,'R');//end of line

  $pdf->Cell(90 ,5,'',0,0);
  $pdf->Cell(25 ,5,'Delivery Fee',0,0);
  $pdf->Cell(10 ,5,'Php',1,0);
  $pdf->Cell(64 ,5,'Php100.00',1,1,'R');//end of line

  $pdf->Cell(90 ,5,'',0,0);
  $pdf->Cell(25 ,5,'Total',0,0);
  $pdf->Cell(10 ,5,'Php',1,0);
  $pdf->Cell(64 ,5,'Php'.number_format($placed_order -> total + 100, 2),1,1,'R');//end of line
  //output the result
  $pdf->Output();
?>
