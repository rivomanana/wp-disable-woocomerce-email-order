<?php
/**
 * Plugin Name: Blocage email produit virtuel
 * Author: H3ry Rivomanana
 * Author URI: https://github.com/rivomanana
 */


add_action('woocommerce_email', function(WC_Emails $args){
    $emails = $args->emails;
    if ( isset($_POST['post_ID']) and isset($_POST['post_type']) ){
        if ( 'shop_order' == $_POST['post_type'] and 'editpost' == $_POST['originalaction'] and 'wc-completed' == $_POST['order_status'] ){
            $order_id = $_POST['post_ID'];
            $order = wc_get_order($order_id);
            $virtual = 0;
            $physical_product = 0;
            /** @var WC_Order_Item_Product $item */
            foreach ( $order->get_items() as $item ){
                $product  = new WC_Product($item->get_product_id());
                if ($product->is_virtual())
                    $virtual ++;
                else
                    $physical_product ++;
            }
            if ( 0 == $physical_product and $virtual > 0 ){
                remove_action('woocommerce_order_status_completed_notification', [$emails['WC_Email_Customer_Completed_Order'], 'trigger']);
            }
        }

    }

});
