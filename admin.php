<?php
add_action('admin_init', 'ebay_flash_store_admin_init');
add_action('admin_menu', 'ebay_flash_store_add_sub_menu_page' );

function ebay_flash_store_admin_init() {
	wp_register_style('ebay_flash_store_back.css', EBAY_FLASH_STORE_PLUGIN_URL . 'back.css');
	wp_enqueue_style('ebay_flash_store_back.css');
}

function ebay_flash_store_admin_configuration() {
  $page_content = "";
  $page_content .= '<div class="ebay_flash_store">';
  $page_content .=  '<h2>'.__("EBay Flash Store - Configuration.").'</h2>';

  $data = array();
  if(isset($_POST['submit']) && isset($_POST['ebay_configuration'])){
    $data = $_POST['ebay_configuration'];
    update_option(EBAY_FLASH_STORE_PLUGIN_VAR_NAME, base64_encode(serialize($data)));
    $page_content .= '<div class="announce">'.__("Successfully updated").'</div>';
  } else {
    $data = get_option(EBAY_FLASH_STORE_PLUGIN_VAR_NAME, array());
    if(is_string($data))
      $data = unserialize(base64_decode($data));
  }

  $page_content .=  ebay_flash_store_get_form($data);
  $page_content .= "</div>";

  echo $page_content;
}

function ebay_flash_store_add_sub_menu_page(){
  if ( function_exists('add_submenu_page') )
    add_submenu_page('plugins.php', __('EBay Flash Store Configuration'), __('EBay Flash Store'), 'manage_options', 'ebay-flash-store-config', 'ebay_flash_store_admin_configuration');
}

function ebay_flash_store_get_form($form_values = array()){
    // Prevent invalid $_POST .
    if(!is_array($form_values))
      exit(__("Invalid form values"));

    $ebay_source_site = array(
          "EBAY-US"   => "USA",
          "EBAY-ENCA" => "Canada",
          "EBAY-GB"   => "United Kingdom",
          "EBAY-AU"   => "Australia",
          "EBAY-AT"   => "Austria",
          "EBAY-FR"   => "France",
          "EBAY-DE"   => "Germany",
          "EBAY-IT"   => "Italy",
          "EBAY-NL"   => "Netherlands",
          "EBAY-ES"   => "Spain",
          "EBAY-CH"   => "Switzerland",
          "EBAY-IE"   => "Ireland",
          "EBAY-FRBE" => "Belgium-fr",
          "EBAY-NLBE" => "Belgium-nl",
    );

    $ebay_item_type = array(
          "All"             => "All",
          "Auction"         => "Auction",
          "AuctionWithBIN"  => "AuctionWithBIN",
          "FixedPrice"      => "FixedPrice",
          "StoreInventory"  => "StoreInventory",
    );

    $ebay_country_selected = array(
          ""          => "No Filter",
          "EBAY-US"   => "USA",
          "EBAY-ENCA" => "Canada",
          "EBAY-GB"   => "United Kingdom",
          "EBAY-AU"   => "Australia",
          "EBAY-AT"   => "Austria",
          "EBAY-FR"   => "France",
          "EBAY-DE"   => "Germany",
          "EBAY-IT"   => "Italy",
          "EBAY-NL"   => "Netherlands",
          "EBAY-ES"   => "Spain",
          "EBAY-CH"   => "Switzerland",
          "EBAY-IE"   => "Ireland",
          "EBAY-FRBE" => "Belgium-fr",
          "EBAY-NLBE" => "Belgium-nl",
    );

    $ebay_item_sort = array(
          "EndTimeSoonest"            => "EndTimeSoonest",
          "StartTimeNewest"           => "StartTimeNewest",
          "PricePlusShippingHighest"  => "PricePlusShippingHighest",
          "PricePlusShippingLowest"   => "PricePlusShippingLowest",
          "CurrentPriceHighest"       => "CurrentPriceHighest",
          "BidCountFewest"            => "BidCountFewest",
          "BidCountMost"              => "BidCountMost",
    );

    $ebay_language_eb = array(
          "eng" => "En US/UK/AU",
          "deu" => "German",
          "fra" => "French",
          "spa" => "Spanish",
          "ita" => "Italian",
          "dut" => "Dutch",
    );

    $ebay_back_status = array(
          "white"       => "White",
          "transparent" => "Transparent",
    );

    $ebay_size_status = array(
          "square"      => "Rectangular (vertical: 250x300)",
          "skyscraper"  => "Skyscraper (vertical: 160x500)",
          "leaderboard" => "Leaderboard (horizontal: 160x500)",
    );
  
    $ret = "";
    $ret .= '<form class="ebay_flash_store" method="post">';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Store Name (case sentisitve)").'</label>';
    $ret .= ' <input type="text" value="%%%storeNamecall%%%" name="ebay_configuration[storeNamecall]">';
    $ret .= ' <div class="description">'.__("Your store name on eBay (case sentisitve)").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Ebay country source").'</label>';
    $ret .=   ebay_flash_store_generateSelectFromArray($ebay_source_site, 'ebay_configuration[sourceSite]', isset($form_values['sourceSite']) ? $form_values['sourceSite'] : "");
    $ret .=   '<div class="description">'.__("Ebay country source").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Maximum items").'</label>';
    $ret .=   '<input type="text" value="%%%maxEntries%%%" name="ebay_configuration[maxEntries]">';
    $ret .=   '<div class="description">'.__("Maximum items").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Type of items").'</label>';
    $ret .=   ebay_flash_store_generateSelectFromArray($ebay_item_type, 'ebay_configuration[itemType]', isset($form_values['itemType']) ? $form_values['itemType'] : "");
    $ret .=   '<div class="description">'.__("Types of Item").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Category ID from eBay (optional)").'</label>';
    $ret .=   '<input type="text" value="%%%categoryId%%%" name="ebay_configuration[categoryId]">';
    $ret .=   '<div class="description">'.__('Category ID from eBay (optional)').'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Keywords (optional)").'</label>';
    $ret .=   '<input type="text" name="keywords" value="%%%keywords%%%"/>';
    $ret .=   '<div class="description">'.__("Optional: if you want to sort only some items by keywords, enter the keywords, in your language, separated by a , if multiple").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Filter items by one country. Optional").'</label>';
    $ret .=   ebay_flash_store_generateSelectFromArray($ebay_country_selected, 'ebay_configuration[country_selected]', isset($form_values['country_selected']) ? $form_values['country_selected'] : "");
    $ret .=   '<div class="description">'.__("Filter items by one country. Only items pushed in this country will appear").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Enter your intro text").'</label>';
    $ret .=   '<textarea name="ebay_configuration[intro]">'."%%%intro%%%".'</textarea>';
    $ret .=   '<div class="description">'.__("Enter your intro text").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Sort order").'</label>';
    $ret .=   ebay_flash_store_generateSelectFromArray($ebay_item_sort, 'ebay_configuration[itemSort]', isset($form_values['itemSort']) ? $form_values['itemSort'] : "");
    $ret .=   '<div class="description">'.__("Choose order in the slideshow. BidCountFewest and BidCountMost are only available for USA, Australia and Germany")
                  ."<br>".("For other places, it'll be replaced by EndTimeSoonest").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Enter slideshow duration in ms").'</label>';
    $ret .=   '<input type="text" value="%%%duration%%%" name="ebay_configuration[duration]">';
    $ret .=   ' <div class="description">'.__("Slideshow duration in ms").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Text to replace Bid now").'</label>';
    $ret .=   '<input type="text" value="%%%bidSentance%%%" name="ebay_configuration[bidSentance]">';
    $ret .=   '<div class="description">'.__("text to replace Bid now. If empty, bid now will be displayed").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Language used for display").'</label>';
    $ret .=   ebay_flash_store_generateSelectFromArray($ebay_language_eb, 'ebay_configuration[languageEB]', isset($form_values['languageEB']) ? $form_values['languageEB'] : "");
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Background").'</label>';
    $ret .=   ebay_flash_store_generateSelectFromArray($ebay_back_status, 'ebay_configuration[backStatus]', isset($form_values['backStatus']) ? $form_values['backStatus'] : "");
    $ret .=   '<div class="description">'.__("White or transparent background").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Display size").'</label>';
    $ret .=   ebay_flash_store_generateSelectFromArray($ebay_size_status, 'ebay_configuration[sizeStatus]', isset($form_values['sizeStatus']) ? $form_values['sizeStatus'] : "");
    $ret .=   '<div class="description">'.__("Choose the size of the flash according to your content").'</div>';
    $ret .= '</div>';
    $ret .= '<div class="field">';
    $ret .=   '<label>'.__("Special Coupon code (optional)").'</label>';
    $ret .=   '<input type="text" value="%%%couponcode%%%" name="ebay_configuration[couponcode]">';
    $ret .= '</div>';
    $ret .= '<div class="clear"></div>';
    $ret .= '<input type="submit" name="submit" value="Save"/>';
    $ret .= '</form>';

    $ret = str_replace("%%%storeNamecall%%%", isset($form_values['storeNamecall']) ? $form_values['storeNamecall'] : "", $ret);
    $ret = str_replace("%%%maxEntries%%%", isset($form_values['maxEntries']) ? $form_values['maxEntries'] : "", $ret);
    $ret = str_replace("%%%categoryId%%%", isset($form_values['categoryId']) ? $form_values['categoryId'] : "", $ret);
    $ret = str_replace("%%%intro%%%", isset($form_values['intro']) ? $form_values['intro'] : "", $ret);
    $ret = str_replace("%%%duration%%%", isset($form_values['duration']) ? $form_values['duration'] : "", $ret);
    $ret = str_replace("%%%bidSentance%%%", isset($form_values['bidSentance']) ? $form_values['bidSentance'] : "", $ret);
    $ret = str_replace("%%%couponcode%%%", isset($form_values['couponcode']) ? $form_values['couponcode'] : "", $ret);
    $ret = str_replace("%%%keywords%%%", isset($form_values['keywords']) ? $form_values['keywords'] : "", $ret);

    return $ret;
}

function ebay_flash_store_generateSelectFromArray($options , $select_name , $selected_option = null){
    $return = "";
    $return .= '<select id="'.$select_name.'" name="'.$select_name.'">';
    foreach($options as $value=>$name){
        $return .= '<option value="'.$value.'"';

        if($value == $selected_option)
            $return .= 'selected="selected"';

        $return .= '>'.$name.'</option>';
    }
    $return .= '</select>';

    return $return;
}
