<?php
  // Data-only class for the complete order's details
  // note~~ OOP programmers pls dont be mad at me
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
    public $billing_address;
    public $shipping_address;
    public $additional_notes;
    public $status;
    public $items;
    public $sale_without_tax;
    public $tax;
    public $total;
  }
?>