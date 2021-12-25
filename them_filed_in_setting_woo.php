add_filter( 'woocommerce_general_settings', 'tmdev_add_shortcode_quote_registration_form' );
function tmdev_add_shortcode_quote_registration_form( $settings ){
  $updated_settings = array();
  foreach ( $settings as $section ) {
    // at the bottom of the General Options section
    if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&
       isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
      $updated_settings[] = array(
        'name'     => 'Đăng ký mua sản phẩm',
        'id'       => 'woocommerce_register_form_product',
        'type'     => 'text',
        'css'      => 'min-width:300px;',
        'std'      => '[contact-form-7 404 "Not Found"]',  // WC < 2.0
        'default'  => '[contact-form-7 404 "Not Found"]',  // WC >= 2.0
        'desc'     => 'vd: [contact-form-7 404 "Not Found"]',
      );
    }
    $updated_settings[] = $section;
  }
  return $updated_settings;
}
