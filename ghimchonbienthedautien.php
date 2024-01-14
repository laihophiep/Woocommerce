add_filter('woocommerce_dropdown_variation_attribute_options_args','fun_select_default_option',10,1);
function fun_select_default_option( $args)
{
    if(count($args['options']) > 0) //Check the count of available options in dropdown
        $args['selected'] = $args['options'][0];
    return $args;
}
