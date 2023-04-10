// Get an instance of the WC_Order object
$order = wc_get_order( $order_id );
$order->get_total()
$order->get_order_number()
$order->get_shipping_method()
$shipping_total = $order->get_shipping_total();
$shipping_tax = $order->get_shipping_tax();
$order_id  = $order->get_id(); // Get the order ID
$parent_id = $order->get_parent_id(); // Get the parent order ID (for subscriptions…)

$user_id   = $order->get_user_id(); // Get the costumer ID
$user      = $order->get_user(); // Get the WP_User object

$order_status  = $order->get_status(); // Get the order status (see the conditional method has_status() below)
$currency      = $order->get_currency(); // Get the currency used  
$payment_method = $order->get_payment_method(); // Get the payment method ID
$payment_title = $order->get_payment_method_title(); // Get the payment method title
$date_created  = $order->get_date_created(); // Get date created (WC_DateTime object)
$date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)

$billing_country = $order->get_billing_country(); // Customer billing country

$order_data = $order->get_data(); // The Order data

$order_id = $order_data['id'];
$order_parent_id = $order_data['parent_id'];
$order_status = $order_data['status'];
$order_currency = $order_data['currency'];
$order_version = $order_data['version'];
$order_payment_method = $order_data['payment_method'];
$order_payment_method_title = $order_data['payment_method_title'];
$order_payment_method = $order_data['payment_method'];
$order_payment_method = $order_data['payment_method'];

## Creation and modified WC_DateTime Object date string ##

// Using a formated date ( with php date() function as method)
$order_date_created = $order_data['date_created']->date('Y-m-d H:i:s');
$order_date_modified = $order_data['date_modified']->date('Y-m-d H:i:s');

// Using a timestamp ( with php getTimestamp() function as method)
$order_timestamp_created = $order_data['date_created']->getTimestamp();
$order_timestamp_modified = $order_data['date_modified']->getTimestamp();

$order_discount_total = $order_data['discount_total'];
$order_discount_tax = $order_data['discount_tax'];
$order_shipping_total = $order_data['shipping_total'];
$order_shipping_tax = $order_data['shipping_tax'];
$order_total = $order_data['cart_tax'];
$order_total_tax = $order_data['total_tax'];
$order_customer_id = $order_data['customer_id']; // ... and so on

## BILLING INFORMATION:

$order_billing_first_name = $order_data['billing']['first_name'];
$order_billing_last_name = $order_data['billing']['last_name'];
$order_billing_company = $order_data['billing']['company'];
$order_billing_address_1 = $order_data['billing']['address_1'];
$order_billing_address_2 = $order_data['billing']['address_2'];
$order_billing_city = $order_data['billing']['city'];
$order_billing_state = $order_data['billing']['state'];
$order_billing_postcode = $order_data['billing']['postcode'];
$order_billing_country = $order_data['billing']['country'];
$order_billing_email = $order_data['billing']['email'];
$order_billing_phone = $order_data['billing']['phone'];

## SHIPPING INFORMATION:

$order_shipping_first_name = $order_data['shipping']['first_name'];
$order_shipping_last_name = $order_data['shipping']['last_name'];
$order_shipping_company = $order_data['shipping']['company'];
$order_shipping_address_1 = $order_data['shipping']['address_1'];
$order_shipping_address_2 = $order_data['shipping']['address_2'];
$order_shipping_city = $order_data['shipping']['city'];
$order_shipping_state = $order_data['shipping']['state'];
$order_shipping_postcode = $order_data['shipping']['postcode'];
$order_shipping_country = $order_data['shipping']['country'];
