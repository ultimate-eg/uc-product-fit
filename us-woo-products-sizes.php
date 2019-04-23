<?php
/*
Plugin Name: US Products sizes
Plugin URI: ahmedsoud3@gmail.com
description: this plugins display new tab in WooCommerce tab to identify customer fit size
Version: 1.0
Author: Ahmed Abdelghffar Soud
License: GPL2
*/
defined( 'ABSPATH' ) or exit;

// plugins activation check
global $woocommerce;
if ( !   in_array( 
    'woocommerce/woocommerce.php', 
    apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
  )) {
    add_action( 'admin_notices', 'uc_woocommerce_admin_notice' );
    return;
}

function uc_woocommerce_admin_notice(){
	?>
	<div class="error">
		<p><?php echo 'UC Product size require WooCommerece to be activated'; ?></p>
	</div>
<?php
}

if ( !   in_array( 
    'advanced-custom-fields-pro/acf.php', 
    apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
  )) {
    add_action( 'admin_notices', 'uc_acf_admin_notice' );
    return;
} 

function uc_acf_admin_notice(){
	?>
	<div class="error">
		<p><?php echo 'UC Product size require ACF pro to be activated'; ?></p>
	</div>
<?php
}


// includes php files
require_once dirname( __FILE__ ) . '/includes/enqueue.php';
require_once dirname( __FILE__ ) . '/includes/validate-plugins.php';
require_once dirname( __FILE__ ) . '/includes/acf-import.php';



// WooCommerce tab
add_filter( 'woocommerce_product_tabs', 'woo_size_tab' );
function woo_size_tab( $tabs ) {

    $terms = get_the_terms( get_the_ID(), 'product_cat' );
    if( get_field('sizes', 'product_cat_' . $terms[0]->term_id ) ){
    $tabs['desc_tab'] = array(
        'title'     => __( 'What is my size', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'woo_size_tab_content'
    );
    return $tabs;
    }
}

function woo_size_tab_content() {
    $terms = get_the_terms( get_the_ID(), 'product_cat' );
    // sizes table
    $sizes = array();

  if( have_rows('sizes', 'product_cat_' . $terms[0]->term_id ) ):
    $i = 0;
    while ( have_rows('sizes', 'product_cat_' . $terms[0]->term_id ) ) : the_row();

      $sizes[$i]['size'] = get_sub_field('size', 'product_cat_' . $terms[0]->term_id );
      $j = 0;
      while ( have_rows('size_attributes', 'product_cat_' . $terms[0]->term_id ) ) : the_row();
        ?>
        <?php
        $sizes[$i]['size_attributes'][$j]['type'] = get_sub_field('type', 'product_cat_' . $terms[0]->term_id );
        $sizes[$i]['size_attributes'][$j]['from'] = get_sub_field('from', 'product_cat_' . $terms[0]->term_id );
        $sizes[$i]['size_attributes'][$j]['to'] = get_sub_field('to', 'product_cat_' . $terms[0]->term_id );
        $j++;
      endwhile;
      $i++;
    endwhile;
  else :
  endif;

  ?>
    <form class="size-wizzard-form" onsubmit="calculateSize(); return false;">
  <?php
  for ($j = 0; $j<sizeof($sizes[0]['size_attributes']); $j++) {
    ?>
      <div class="form-group">
          <input required type="number" placeholder="<?php echo $sizes[0]['size_attributes'][$j]['type'] ?>" min="0" class="form-control size-inputs" id="<?php echo $sizes[0]['size_attributes'][$j]['type'] ?>">
      </div>
    <?php
  }
  $sizes_json = json_encode($sizes);
  if (sizeof($sizes > 0)) {
    ?>
      <input id="size-wizard" value="Check Your Size" onclick="displaySizeWizard()" class="btn btn-default"></button>
      <input id="size-wizard-next" value="Next" onclick="nextInput()" class="btn btn-default"></button>
      <input type="submit" id="calculateSizeBtn" data='<?php echo print_r($sizes_json); ?>' value="Calculate Size" onclick="" class="btn btn-default"></button>
      </form>
      <div id="size-result"> </div>
    <?php
  }

    if( have_rows('sizes', 'product_cat_' . $terms[0]->term_id ) ):
        $i = 0;
        while ( have_rows('sizes', 'product_cat_' . $terms[0]->term_id ) ) : the_row();
            ?>
                <div class="panel panel-default">
                    <h3 class="panel-heading"><?php the_sub_field('size', 'product_cat_' . $terms[0]->term_id ); ?></h3>
                <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>From(cm)</th>
                        <th>To(cm)</th>
                    </tr>
                    </thead>
                <tbody>
            <?php
            $j = 0;
            while ( have_rows('size_attributes', 'product_cat_' . $terms[0]->term_id ) ) : the_row();
                ?>
                <tr>
                    <td><?php the_sub_field('type', 'product_cat_' . $terms[0]->term_id ); ?></td>
                    <td><?php the_sub_field('from', 'product_cat_' . $terms[0]->term_id ); ?></td>
                    <td><?php the_sub_field('to', 'product_cat_' . $terms[0]->term_id ); ?></td>
                </tr>    
                <?php
                $j++;
            endwhile;
            $i++;
                ?>
                </tbody>
                </table>
                </div>
                <?php
        endwhile;
    else :
    endif;


    // calculation form
    

}
