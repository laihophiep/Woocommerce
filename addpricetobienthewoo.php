function trendy_display_price_variation_option_name( $term ) {
    $product = wc_get_product();
    if (is_product()){
        $id = $product->get_id();
    } else {$id ='';}
    if ( empty( $term ) || empty( $id ) ) {
        return $term;
    }
    if ( $product->is_type( 'variable' ) ) {
        $product_variations = $product->get_available_variations();
    } else {
        return $term;
    }
    foreach($product_variations as $variation){
        if(count($variation['attributes']) > 1){
            return $term;
        }
        foreach($variation['attributes'] as $key => $slug){
            if("attribute_" == mb_substr( $key, 0, 10 )){
                $taxonomy = mb_substr( $key, 10 ) ;
                $attribute = get_term_by('slug', $slug, $taxonomy);
                if(($attribute->name == $term)  && (is_product())){
                    $term .= "<strong>" . wp_kses( wc_price($variation['display_price']), array()) . "</strong>";
                }
            }
        }
    }
    return $term;
}
add_filter( 'woocommerce_variation_option_name', 'trendy_display_price_variation_option_name' );
2. Chuyển định dạng từ selected sang radio
Từ thư mục của theme child tạo các folder tương ứng dẫn đến file variable.php

mình đang dùng theme flatsome nên đường dẫn như sau: flatsome-child\woocommerce\single-product\add-to-cart\variable.php

<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;
global $product;
$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
   <?php do_action( 'woocommerce_before_variations_form' ); ?>
   <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
      <p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
   <?php else : ?>
      <table class="variations" cellspacing="0">
         <tbody>
            <?php foreach ( $attributes as $attribute_name => $options ) : ?>
               <?php $sanitized_name = sanitize_title( $attribute_name ); ?>
               <tr class="attribute-<?php echo esc_attr( $sanitized_name ); ?>">
                  <td class="label"><label for="<?php echo esc_attr( $sanitized_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>
                  <?php
                  if ( isset( $_REQUEST[ 'attribute_' . $sanitized_name ] ) ) {
                     $checked_value = $_REQUEST[ 'attribute_' . $sanitized_name ];
                  } elseif ( isset( $selected_attributes[ $sanitized_name ] ) ) {
                     $checked_value = $selected_attributes[ $sanitized_name ];
                  } else {
                     $checked_value = '';
                  }
                  ?>
               <td class="value">
               <div class="trendy-radio-variable">
                     <?php
                        if ( ! empty( $options ) ) {
                        if ( taxonomy_exists( $attribute_name ) ) {
                           // Get terms if this is a taxonomy - ordered. We need the names too.
                           $terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );

                           foreach ( $terms as $term ) {
                              if ( ! in_array( $term->slug, $options ) ) {
                                 continue;
                              }
                              
                              trendy_print_attribute_radio( $checked_value, $term->slug, $term->name, $sanitized_name );
                          
                           }
                        } else {
                           foreach ( $options as $option ) {
                              trendy_print_attribute_radio( $checked_value, $option, $option, $sanitized_name );
                           }
                        }
                     };
                        echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
                     ?>
                     </div>
                  </td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
      <div class="single_variation_wrap">
         <?php
            /**
             * Hook: woocommerce_before_single_variation.
             */
            do_action( 'woocommerce_before_single_variation' );

            /**
             * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
             *
             * @since 2.4.0
             * @hooked woocommerce_single_variation - 10 Empty div for variation data.
             * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
             */
            do_action( 'woocommerce_single_variation' );

            /**
             * Hook: woocommerce_after_single_variation.
             */
            do_action( 'woocommerce_after_single_variation' );
         ?>
      </div>
   <?php endif; ?>
   <?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>
<?php
do_action( 'woocommerce_after_add_to_cart_form' );
Thêm đoạn code này vào file variable.php

function trendy_print_attribute_radio( $checked_value, $value, $label, $attribute_name ) {
         global $product;
         $input_name = 'attribute_' . esc_attr( $attribute_name ) ;
         $esc_value = esc_attr( $value );
         $id = esc_attr( $attribute_name . '_v_' . $value . $product->get_id() ); //added product ID at the end of the name to target single products
         $checked = checked( $checked_value, $value, false );
         $active='';
         if( $checked = checked( $checked_value, $value, false )){$active.='active';}
         $filtered_label = apply_filters( 'woocommerce_variation_option_name', $label, esc_attr( $attribute_name ) );
         printf( '<label class="trendy-radio-variable-option '.$active.'"><input type="radio" name="%1$s" value="%2$s" id="%3$s" %4$s><span class="radio-variable-name">%5$s</span></label>', $input_name, $esc_value, $id, $checked, $filtered_label );
      }
Thêm đoạn code này vào file functions.php

3. Thêm scripts 
Để thay đổi giá hiển thị khi bấm vào radio variable thì chúng ta cần phải thêm scripts

tạo file scripts trong thư mục theme child với tên: add-to-cart-variation.js

thêm đoạn code này vào file add-to-cart-variation.js

/*global wc_add_to_cart_variation_params, wc_cart_fragments_params */

;(function ( $, window, document, undefined ) {
   $(function(){
      $('.trendy-radio-variable-option').on('click', function(){
         $(this).addClass('active').siblings().removeClass('active');
      });
      $('.reset_variations').on('click', function(){
         $('.trendy-radio-variable-option').removeClass('active');
      });

   });
   /**
    * VariationForm class which handles variation forms and attributes.
    */
   var VariationForm = function( $form ) {
      this.$form                = $form;
      this.$attributeGroups     = $form.find( '.variations .value' );
      this.$attributeFields     = $form.find( '.variations input[type=radio]' );
      this.$singleVariation     = $form.find( '.single_variation' );
      this.$singleVariationWrap = $form.find( '.single_variation_wrap' );
      this.$resetVariations     = $form.find( '.reset_variations' );
      this.$product             = $form.closest( '.product' );
      this.variationData        = $form.data( 'product_variations' );
      this.useAjax              = false === this.variationData;
      this.xhr                  = false;

      // Initial state.
      this.$singleVariationWrap.show();
      this.$form.off( '.wc-variation-form' );

      // Methods.
      this.getChosenAttributes    = this.getChosenAttributes.bind( this );
      this.findMatchingVariations = this.findMatchingVariations.bind( this );
      this.isMatch                = this.isMatch.bind( this );
      this.toggleResetLink        = this.toggleResetLink.bind( this );

      // Events.
      $form.on( 'click.wc-variation-form', '.reset_variations', { variationForm: this }, this.onReset );
      $form.on( 'reload_product_variations', { variationForm: this }, this.onReload );
      $form.on( 'hide_variation', { variationForm: this }, this.onHide );
      $form.on( 'show_variation', { variationForm: this }, this.onShow );
      $form.on( 'click', '.single_add_to_cart_button', { variationForm: this }, this.onAddToCart );
      $form.on( 'reset_data', { variationForm: this }, this.onResetDisplayedVariation );
      $form.on( 'reset_image', { variationForm: this }, this.onResetImage );
      $form.on( 'change.wc-variation-form', '.variations input[type=radio]', { variationForm: this }, this.onChange );
      $form.on( 'found_variation.wc-variation-form', { variationForm: this }, this.onFoundVariation );
      $form.on( 'check_variations.wc-variation-form', { variationForm: this }, this.onFindVariation );
      $form.on( 'update_variation_values.wc-variation-form', { variationForm: this }, this.onUpdateAttributes );

      // Check variations once init.
      // Init after gallery.
      setTimeout( function() {
         $form.trigger( 'check_variations' );
         $form.trigger( 'wc_variation_form' );
      }, 100 );
   };

   /**
    * Reset all fields.
    */
   VariationForm.prototype.onReset = function( event ) {
      event.preventDefault();
      event.data.variationForm.$attributeFields.prop( 'checked', false ).change();
      event.data.variationForm.$form.trigger( 'reset_data' );
   };

   /**
    * Reload variation data from the DOM.
    */
   VariationForm.prototype.onReload = function( event ) {
      var form           = event.data.variationForm;
      form.variationData = form.$form.data( 'product_variations' );
      form.useAjax       = false === form.variationData;
      form.$form.trigger( 'check_variations' );
   };

   /**
    * When a variation is hidden.
    */
   VariationForm.prototype.onHide = function( event ) {
      event.preventDefault();
      event.data.variationForm.$form.find( '.single_add_to_cart_button' ).removeClass( 'wc-variation-is-unavailable' ).addClass( 'disabled wc-variation-selection-needed' );
      event.data.variationForm.$form.find( '.woocommerce-variation-add-to-cart' ).removeClass( 'woocommerce-variation-add-to-cart-enabled' ).addClass( 'woocommerce-variation-add-to-cart-disabled' );
   };

   /**
    * When a variation is shown.
    */
   VariationForm.prototype.onShow = function( event, variation, purchasable ) {
      event.preventDefault();
      if ( purchasable ) {
         event.data.variationForm.$form.find( '.single_add_to_cart_button' ).removeClass( 'disabled wc-variation-selection-needed wc-variation-is-unavailable' );
         event.data.variationForm.$form.find( '.woocommerce-variation-add-to-cart' ).removeClass( 'woocommerce-variation-add-to-cart-disabled' ).addClass( 'woocommerce-variation-add-to-cart-enabled' );
      } else {
         event.data.variationForm.$form.find( '.single_add_to_cart_button' ).removeClass( 'wc-variation-selection-needed' ).addClass( 'disabled wc-variation-is-unavailable' );
         event.data.variationForm.$form.find( '.woocommerce-variation-add-to-cart' ).removeClass( 'woocommerce-variation-add-to-cart-enabled' ).addClass( 'woocommerce-variation-add-to-cart-disabled' );
      }
   };

   /**
    * When the cart button is pressed.
    */
   VariationForm.prototype.onAddToCart = function( event ) {
      if ( $( this ).is('.disabled') ) {
         event.preventDefault();

         if ( $( this ).is('.wc-variation-is-unavailable') ) {
            window.alert( wc_add_to_cart_variation_params.i18n_unavailable_text );
         } else if ( $( this ).is('.wc-variation-selection-needed') ) {
            window.alert( wc_add_to_cart_variation_params.i18n_make_a_selection_text );
         }
      }
   };

   /**
    * When displayed variation data is reset.
    */
   VariationForm.prototype.onResetDisplayedVariation = function( event ) {
      var form = event.data.variationForm;
      form.$product.find( '.product_meta' ).find( '.sku' ).wc_reset_content();
      form.$product.find( '.product_weight' ).wc_reset_content();
      form.$product.find( '.product_dimensions' ).wc_reset_content();
      form.$form.trigger( 'reset_image' );
      form.$singleVariation.slideUp( 200 ).trigger( 'hide_variation' );
   };

   /**
    * When the product image is reset.
    */
   VariationForm.prototype.onResetImage = function( event ) {
      event.data.variationForm.$form.wc_variations_image_update( false );
   };

   /**
    * Looks for matching variations for current checked attributes.
    */
   VariationForm.prototype.onFindVariation = function( event ) {
      var form              = event.data.variationForm,
         attributes        = form.getChosenAttributes(),
         currentAttributes = attributes.data;

      if ( attributes.count === attributes.chosenCount ) {
         if ( form.useAjax ) {
            if ( form.xhr ) {
               form.xhr.abort();
            }
            form.$form.block( { message: null, overlayCSS: { background: '#fff', opacity: 0.6 } } );
            currentAttributes.product_id  = parseInt( form.$form.data( 'product_id' ), 10 );
            currentAttributes.custom_data = form.$form.data( 'custom_data' );
            form.xhr                      = $.ajax( {
               url: wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_variation' ),
               type: 'POST',
               data: currentAttributes,
               success: function( variation ) {
                  if ( variation ) {
                     form.$form.trigger( 'found_variation', [ variation ] );
                  } else {
                     form.$form.trigger( 'reset_data' );
                     form.$form.find( '.single_variation' ).after( '<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>' );
                     form.$form.find( '.wc-no-matching-variations' ).slideDown( 200 );
                  }
               },
               complete: function() {
                  form.$form.unblock();
               }
            } );
         } else {
            form.$form.trigger( 'update_variation_values' );

            var matching_variations = form.findMatchingVariations( form.variationData, currentAttributes ),
               variation           = matching_variations.shift();

            if ( variation ) {
               form.$form.trigger( 'found_variation', [ variation ] );
            } else {
               form.$form.trigger( 'reset_data' );
               form.$form.find( '.single_variation' ).after( '<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>' );
               form.$form.find( '.wc-no-matching-variations' ).slideDown( 200 );
            }
         }
      } else {
         form.$form.trigger( 'update_variation_values' );
         form.$form.trigger( 'reset_data' );
      }

      // Show reset link.
      form.toggleResetLink( attributes.chosenCount > 0 );
   };

   /**
    * Triggered when a variation has been found which matches all attributes.
    */
   VariationForm.prototype.onFoundVariation = function( event, variation ) {
      var form           = event.data.variationForm,
         $sku           = form.$product.find( '.product_meta' ).find( '.sku' ),
         $weight        = form.$product.find( '.product_weight' ),
         $dimensions    = form.$product.find( '.product_dimensions' ),
         $qty           = form.$singleVariationWrap.find( '.quantity' ),
         purchasable    = true,
         variation_id   = '',
         template       = false,
         $template_html = '';

      if ( variation.sku ) {
         $sku.wc_set_content( variation.sku );
      } else {
         $sku.wc_reset_content();
      }

      if ( variation.weight ) {
         $weight.wc_set_content( variation.weight_html );
      } else {
         $weight.wc_reset_content();
      }

      if ( variation.dimensions ) {
         $dimensions.wc_set_content( variation.dimensions_html );
      } else {
         $dimensions.wc_reset_content();
      }

      form.$form.wc_variations_image_update( variation );

      if ( ! variation.variation_is_visible ) {
         template = wp.template( 'unavailable-variation-template' );
      } else {
         template     = wp.template( 'variation-template' );
         variation_id = variation.variation_id;
      }

      $template_html = template( {
         variation: variation
      } );
      $template_html = $template_html.replace( '/*<![CDATA[*/', '' );
      $template_html = $template_html.replace( '/*]]>*/', '' );

      form.$singleVariation.html( $template_html );
      form.$form.find( 'input[name="variation_id"], input.variation_id' ).val( variation.variation_id ).change();

      // Hide or show qty input
      if ( variation.is_sold_individually === 'yes' ) {
         $qty.find( 'input.qty' ).val( '1' ).attr( 'min', '1' ).attr( 'max', '' );
         $qty.hide();
      } else {
         $qty.find( 'input.qty' ).attr( 'min', variation.min_qty ).attr( 'max', variation.max_qty );
         $qty.show();
      }

      // Enable or disable the add to cart button
      if ( ! variation.is_purchasable || ! variation.is_in_stock || ! variation.variation_is_visible ) {
         purchasable = false;
      }

      // Reveal
      if ( $.trim( form.$singleVariation.text() ) ) {
         form.$singleVariation.slideDown( 200 ).trigger( 'show_variation', [ variation, purchasable ] );
      } else {
         form.$singleVariation.show().trigger( 'show_variation', [ variation, purchasable ] );
      }
   };

   /**
    * Triggered when an attribute field changes.
    */
   VariationForm.prototype.onChange = function( event ) {
      var form = event.data.variationForm;

      form.$form.find( 'input[name="variation_id"], input.variation_id' ).val( '' ).change();
      form.$form.find( '.wc-no-matching-variations' ).remove();

      if ( form.useAjax ) {
         form.$form.trigger( 'check_variations' );
      } else {
         form.$form.trigger( 'woocommerce_variation_select_change' );
         form.$form.trigger( 'check_variations' );
         $( this ).blur();
      }

      // Custom event for when variation selection has been changed
      form.$form.trigger( 'woocommerce_variation_has_changed' );
   };

   /**
    * Escape quotes in a string.
    * @param {string} string
    * @return {string}
    */
   VariationForm.prototype.addSlashes = function( string ) {
      string = string.replace( /'/g, '\\\'' );
      string = string.replace( /"/g, '\\\"' );
      return string;
   };

   /**
    * Updates attributes in the DOM to show valid values.
    */
   VariationForm.prototype.onUpdateAttributes = function( event ) {
      var form              = event.data.variationForm,
         attributes        = form.getChosenAttributes(),
         currentAttributes = attributes.data;

      if ( form.useAjax ) {
         return;
      }

      // Loop through radio buttons and disable/enable based on selections.
      form.$attributeGroups.each( function( index, el ) {
         var current_attr      = $( el ),
            $fields           = current_attr.find( 'input[type=radio]' ),
            current_attr_name = $fields.data( 'attribute_name' ) || $fields.attr( 'name' );

         // The attribute of this radio button should not be taken into account when calculating its matching variations:
         // The constraints of this attribute are shaped by the values of the other attributes.
         var checkAttributes = $.extend( true, {}, currentAttributes );

         checkAttributes[ current_attr_name ] = '';

         var variations = form.findMatchingVariations( form.variationData, checkAttributes );

         $fields.prop( 'disabled', 'disabled' );

         // Loop through variations.
         for ( var num in variations ) {
            if ( typeof( variations[ num ] ) !== 'undefined' && variations[ num ].variation_is_active ) {
               var variationAttributes = variations[ num ].attributes;

               for ( var attr_name in variationAttributes ) {
                  if ( variationAttributes.hasOwnProperty( attr_name ) && attr_name === current_attr_name  ) {
                     var attr_val = variationAttributes[ attr_name ];

                     if ( attr_val ) {
                        // Remove disabled.
                        $fields.filter( '[value="' + form.addSlashes( attr_val ) + '"]' ).prop( 'disabled', false );
                     } else {
                        // Enable all radio buttons of attribute.
                        $fields.prop( 'disabled', false );
                     }
                  }
               }
            }
         }
      });

      // Custom event for when variations have been updated.
      form.$form.trigger( 'woocommerce_update_variation_values' );
   };

   /**
    * Get chosen attributes from form.
    * @return array
    */
   VariationForm.prototype.getChosenAttributes = function() {
      var data   = {};
      var count  = 0;
      var chosen = 0;

      this.$attributeGroups.each( function() {
         var $fields = $( this ).find( 'input[type=radio]' );

         var attribute_name = $fields.data( 'attribute_name' ) || $fields.attr( 'name' );
         var value          = $fields.filter(':checked').val() || '';

         if ( value.length > 0 ) {
            chosen ++;
         }

         count ++;
         data[ attribute_name ] = value;
      });

      return {
         'count'      : count,
         'chosenCount': chosen,
         'data'       : data
      };
   };

   /**
    * Find matching variations for attributes.
    */
   VariationForm.prototype.findMatchingVariations = function( variations, attributes ) {
      var matching = [];
      for ( var i = 0; i < variations.length; i++ ) {
         var variation = variations[i];

         if ( this.isMatch( variation.attributes, attributes ) ) {
            matching.push( variation );
         }
      }
      return matching;
   };

   /**
    * See if attributes match.
    * @return {Boolean}
    */
   VariationForm.prototype.isMatch = function( variation_attributes, attributes ) {
      var match = true;
      for ( var attr_name in variation_attributes ) {
         if ( variation_attributes.hasOwnProperty( attr_name ) ) {
            var val1 = variation_attributes[ attr_name ];
            var val2 = attributes[ attr_name ];
            if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
               match = false;
            }
         }
      }
      return match;
   };

   /**
    * Show or hide the reset link.
    */
   VariationForm.prototype.toggleResetLink = function( on ) {
      if ( on ) {
         if ( this.$resetVariations.css( 'visibility' ) === 'hidden' ) {
            this.$resetVariations.css( 'visibility', 'visible' ).hide().fadeIn();
         }
      } else {
         this.$resetVariations.css( 'visibility', 'hidden' );
      }
   };

   /**
    * Function to call wc_variation_form on jquery selector.
    */
   $.fn.wc_variation_form = function() {
      new VariationForm( this );
      return this;
   };

   /**
    * Stores the default text for an element so it can be reset later
    */
   $.fn.wc_set_content = function( content ) {
      if ( undefined === this.attr( 'data-o_content' ) ) {
         this.attr( 'data-o_content', this.text() );
      }
      this.text( content );
   };

   /**
    * Stores the default text for an element so it can be reset later
    */
   $.fn.wc_reset_content = function() {
      if ( undefined !== this.attr( 'data-o_content' ) ) {
         this.text( this.attr( 'data-o_content' ) );
      }
   };

   /**
    * Stores a default attribute for an element so it can be reset later
    */
   $.fn.wc_set_variation_attr = function( attr, value ) {
      if ( undefined === this.attr( 'data-o_' + attr ) ) {
         this.attr( 'data-o_' + attr, ( ! this.attr( attr ) ) ? '' : this.attr( attr ) );
      }
      if ( false === value ) {
         this.removeAttr( attr );
      } else {
         this.attr( attr, value );
      }
   };

   /**
    * Reset a default attribute for an element so it can be reset later
    */
   $.fn.wc_reset_variation_attr = function( attr ) {
      if ( undefined !== this.attr( 'data-o_' + attr ) ) {
         this.attr( attr, this.attr( 'data-o_' + attr ) );
      }
   };

   /**
    * Reset the slide position if the variation has a different image than the current one
    */
   $.fn.wc_maybe_trigger_slide_position_reset = function( variation ) {
      var $form                = $( this ),
         $product             = $form.closest( '.product' ),
         $product_gallery     = $product.find( '.images' ),
         reset_slide_position = false,
         new_image_id = ( variation && variation.image_id ) ? variation.image_id : '';

      if ( $form.attr( 'current-image' ) !== new_image_id ) {
         reset_slide_position = true;
      }

      $form.attr( 'current-image', new_image_id );

      if ( reset_slide_position ) {
         $product_gallery.trigger( 'woocommerce_gallery_reset_slide_position' );
      }
   };

   /**
    * Sets product images for the chosen variation
    */
   $.fn.wc_variations_image_update = function( variation ) {
      var $form             = this,
         $product          = $form.closest( '.product' ),
         $product_gallery  = $product.find( '.images' ),
         $gallery_nav      = $product.find( '.flex-control-nav' ),
         $gallery_img      = $gallery_nav.find( 'li:eq(0) img' ),
         $product_img_wrap = $product_gallery.find( '.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder' ).eq( 0 ),
         $product_img      = $product_img_wrap.find( '.wp-post-image' ),
         $product_link     = $product_img_wrap.find( 'a' ).eq( 0 );

      if ( variation && variation.image && variation.image.src && variation.image.src.length > 1 ) {
         if ( $gallery_nav.find( 'li img[src="' + variation.image.thumb_src + '"]' ).length > 0 ) {
            $gallery_nav.find( 'li img[src="' + variation.image.thumb_src + '"]' ).trigger( 'click' );
            $form.attr( 'current-image', variation.image_id );
            return;
         } else {
            $product_img.wc_set_variation_attr( 'src', variation.image.src );
            $product_img.wc_set_variation_attr( 'height', variation.image.src_h );
            $product_img.wc_set_variation_attr( 'width', variation.image.src_w );
            $product_img.wc_set_variation_attr( 'srcset', variation.image.srcset );
            $product_img.wc_set_variation_attr( 'sizes', variation.image.sizes );
            $product_img.wc_set_variation_attr( 'title', variation.image.title );
            $product_img.wc_set_variation_attr( 'alt', variation.image.alt );
            $product_img.wc_set_variation_attr( 'data-src', variation.image.full_src );
            $product_img.wc_set_variation_attr( 'data-large_image', variation.image.full_src );
            $product_img.wc_set_variation_attr( 'data-large_image_width', variation.image.full_src_w );
            $product_img.wc_set_variation_attr( 'data-large_image_height', variation.image.full_src_h );
            $product_img_wrap.wc_set_variation_attr( 'data-thumb', variation.image.src );
            $gallery_img.wc_set_variation_attr( 'src', variation.image.thumb_src );
            $product_link.wc_set_variation_attr( 'href', variation.image.full_src );
         }
      } else {
         $product_img.wc_reset_variation_attr( 'src' );
         $product_img.wc_reset_variation_attr( 'width' );
         $product_img.wc_reset_variation_attr( 'height' );
         $product_img.wc_reset_variation_attr( 'srcset' );
         $product_img.wc_reset_variation_attr( 'sizes' );
         $product_img.wc_reset_variation_attr( 'title' );
         $product_img.wc_reset_variation_attr( 'alt' );
         $product_img.wc_reset_variation_attr( 'data-src' );
         $product_img.wc_reset_variation_attr( 'data-large_image' );
         $product_img.wc_reset_variation_attr( 'data-large_image_width' );
         $product_img.wc_reset_variation_attr( 'data-large_image_height' );
         $product_img_wrap.wc_reset_variation_attr( 'data-thumb' );
         $gallery_img.wc_reset_variation_attr( 'src' );
         $product_link.wc_reset_variation_attr( 'href' );
      }

      window.setTimeout( function() {
         $( window ).trigger( 'resize' );
         $form.wc_maybe_trigger_slide_position_reset( variation );
         $product_gallery.trigger( 'woocommerce_gallery_init_zoom' );
      }, 20 );
   };

   $(function() {
      if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
         $( '.variations_form' ).each( function() {
            $( this ).wc_variation_form();
         });
      }
   });

   /**
    * Matches inline variation objects to chosen attributes
    * @deprecated 2.6.9
    * @type {Object}
    */
   var wc_variation_form_matcher = {
      find_matching_variations: function( product_variations, settings ) {
         var matching = [];
         for ( var i = 0; i < product_variations.length; i++ ) {
            var variation    = product_variations[i];

            if ( wc_variation_form_matcher.variations_match( variation.attributes, settings ) ) {
               matching.push( variation );
            }
         }
         return matching;
      },
      variations_match: function( attrs1, attrs2 ) {
         var match = true;
         for ( var attr_name in attrs1 ) {
            if ( attrs1.hasOwnProperty( attr_name ) ) {
               var val1 = attrs1[ attr_name ];
               var val2 = attrs2[ attr_name ];
               if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
                  match = false;
               }
            }
         }
         return match;
      }
   };

})( jQuery, window, document );

tiếp đến thêm đoạn code này vào file functions.php

function trendy_load_scripts_variation() {
         // Don't load JS if current product type is bundle to prevent the page from not working
         if (!(wc_get_product() && wc_get_product()->is_type('bundle'))) {
            wp_deregister_script( 'wc-add-to-cart-variation' );
            wp_enqueue_script( 'wc-add-to-cart-variation', get_stylesheet_directory_uri() . '/assets/js/frontend/add-to-cart-variation.js', array( 'jquery' ) );
         }
      }
add_action( 'wp_enqueue_scripts', 'trendy_load_scripts_variation' );
4. Thêm Css tùy biến cho đẹp
.variations:before {content: "Hãy lựa chọn theo sở thích của bạn";font-size: 15px;font-weight: 600}
.variations .reset_variations{bottom: 80%}
.variations tr {display: flex;flex-direction: column}
.variations .trendy-radio-variable-option input[type='radio'], td.label {display: none}
.variations .trendy-radio-variable-option{display: inline-block;padding: 5px;margin-right: 6px;background: #fbfbfb;box-shadow: 0 2px 3px 0 rgb(0 0 0 / 15%);border-radius: 10px;cursor: pointer;border: 1px solid #f2f3f7;min-width: 75px;width: 23.6%}
.variations .trendy-radio-variable-option .radio-variable-name:before {content: "";display: inline-block;width: 14px;height: 14px;line-height: 14px;background: #fff;border: 1px solid #ddd;text-align: center;position: absolute;left: 0;top: 6px;color: #fff;cursor: pointer;font-family: "Font Awesome 5 Free";font-weight: 700;font-size: 8px;border-radius: 99px}
.variations .trendy-radio-variable-option.active .radio-variable-name {font-weight: 700}
.variations .trendy-radio-variable-option.active .radio-variable-name:before {width: 14px;height: 14px;position: absolute;border: none;content: " ";background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAABGdBTUEAALGPC/xhBQAAA2pJREFUSA29VltIVFEUXeeOo/jKykrIB75IMws1k4keTpCaUVHUT9Ljo6+IECVDwlLSCskIkqDHR0H0gDICIxs1MiQyDfXDIsvSMjW0SLEstZnb2de5lzMzjo9RunA55+y99lpzzpx992aYwrOxKj1+VLZsh4x1/A1kTF5MYbLMusHQxd9aPZMeVKdUNE1GxyYCGE3puyBbimTIURPhVB8DawWT8mrSKu6pNvtxXMFU09awEQzfkmUY7AOmsmYMde7wyKhMK2+3xzsIbqhMT5ZlcxkX87cHT2fNRb8zptv5NLXimRhnI0hiFtlcxf8nvQhyec4wKjFdiiiqCVqPsWGmO7P/cbRTfryr1OOVVID1P5vRMapc4kgbIG7VpgjSbXT1gqhE4nggcj/KjLcR6KVkD08fGJQbz0FjO+RXXwyYyXxP+G7sjciAr97HlsaqwZSkNpsbbb2urbYEbcaRZZkwy2YUNJ9Cbe9zGyK9TpcgKV8QG7Nri+SAtciOOawEn39T6iBGDtKSlM+VaxpaVML8OOStyIXEJFx9dw0Pv1RoPpsJ/zSSYKCNcZqLqDlLUBRfAL2kx92O+7jZfsc5A9eS1A+xc5RzT7BXEIpXFsHLzROV3U9wsfWyczD3kJaWhyIy2i8KnjpP0eQwX+Dhj5LEM5jr7oe6vnoUt5Q4YOwNvLrIklJiBE+M31JcMlzA9TVXQMLjPT5uPjibeBoBnovQ8uM18puL+M20jAe1t/VISj0TzG2DHxQSIitNOocdIdsEL+AhefBjLESYTyg+DnYgt/EEhi3DNhinC147daH7IsP5xUlWQZRDpu5qePEjXT4vFoaFSQjxDkb9t1ccZkFhfD4S/OPQ8/srshpyMDA6oIZOPkrsxoSJT7l1NDYb3m7e+PyrEx0/P2E9t/WP9OPQyyx0DXVPLiIgKPGVamF8nPbWWVWn7+HJuOOI8A1XQof+DiGzPgfvB9sEqsmn1A3UbDJFj91S3hY4C6FdHKzLRHnnI/T+6cOxpoJpiyncVg2tHhpNqS9ms2KIG6CWoyatcjXZtDykHoSKpQicjbm1AGeoXJogVWTqQXiajKrOGY+cizjVak98miAtqPegHmQ2dkoc9v2Mg6AqSj0ID6ijtSsPxRKH2DypPNqlUQ3i+N8aYVGU5rPZ6v8DM8FB6Mp4ZBcAAAAASUVORK5CYII=) 0 0/14px 14px;}
.variations .trendy-radio-variable-option.active {border: 1px solid #ae1215}
.variations .radio-variable-name {padding: 5px 5px 5px 20px;display: block;position: relative;font-size: 12px;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;font-weight: 500;line-height: 18px} 
.variations .radio-variable-name strong {font-size: 13px;color: #cb1818;margin-left: -15px;display: block}
