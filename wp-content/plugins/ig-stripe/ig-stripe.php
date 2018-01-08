<?php
/*
    Plugin Name: IG_Stripe
    Plugin URI: http://is_empty_today.com
    Description: TZ stripe
    Version: 1.0
    Author: iG
    Author URI: http://flat.h1n.ru/tz/
*/
?>

<?php

require('lib/stripe/init.php');

\Stripe\Stripe::setApiKey(getenv('sk_test_HZmmo9b1uaFt1odr6Qi4JtAU'));
\Stripe\Stripe::setClientId(getenv('pk_test_SNg3w60rlauTccgbliTL8hPz'));


function ig_auth()
{
    if (isset($_GET['code'])) {
        // The user was redirected back from the OAuth form with an authorization code.
        $code = $_GET['code'];

        try {
            $resp = \Stripe\OAuth::token(array(
                'grant_type' => 'authorization_code',
                'code' => $code,
            ));
        } catch (\Stripe\Error\OAuth\OAuthBase $e) {
            exit("Error: " . $e->getMessage());
        }

        $accountId = $resp->stripe_user_id;

        echo "<p>Success! Account <code>$accountId</code> is connected.</p>\n";
        echo "<p>Click <a href=\"?deauth=$accountId\">here</a> to disconnect the account.</p>\n";

    } elseif (isset($_GET['error'])) {
        // The user was redirect back from the OAuth form with an error.
        $error = $_GET['error'];
        $error_description = $_GET['error_description'];

        echo "<p>Error: code=$error, description=$error_description</p>\n";
        echo "<p>Click <a href=\"?\">here</a> to restart the OAuth flow.</p>\n";

    } elseif (isset($_GET['deauth'])) {
        // Deauthorization request
        $accountId = $_GET['deauth'];

        try {
            \Stripe\OAuth::deauthorize(array(
                'stripe_user_id' => $accountId,
            ));
        } catch (\Stripe\Error\OAuth\OAuthBase $e) {
            exit("Error: " . $e->getMessage());
        }

        echo "<p>Success! Account <code>$accountId</code> is disonnected.</p>\n";
        echo "<p>Click <a href=\"?\">here</a> to restart the OAuth flow.</p>\n";

    } else {
        $url = \Stripe\OAuth::authorizeUrl(array(
            'scope' => 'read_only',
        ));
        echo "<a href=\"$url\">Connect with Stripe</a>\n";
    }
}





function register_my_custom_gateway($gateways){
    $gateways['my_gateway'] = array(
        'name' => 'Custom Gateway',
        'form_name' => 'gateway_form',
        'amount_name' => 'amount'
    );
    return $gateways;
}
//add_filter('reservations_register_gateway', 'register_my_custom_gateway', 10, 1);



function generate_my_gateway_payment_form($res,$id,$title,$price,$nonce){

    $form = '<form name="gateway_form" action="' . get_site_url() . '" method="post" id="easy_paypal_form">';

    $array = array(
        'invoice' => $id,
        'item_name' => $title,
        'amount' => $price,
        'notify_url' => WP_PLUGIN_URL.'/example/example_ipn.php',
        'custom' => $nonce,
        'business' => 'my_shop_identifier',
        'cancel_return' => get_site_url() . '/cancel/',
        'currency_code' => 'USD',
        'return' => get_site_url() . '/thanks/'
    );

    $form .= easyreservations_generate_hidden_fields($array);
    $form .= '</form>';

    return $form;
}
//add_filter('reservations_generate_gateway_button', 'generate_my_gateway_payment_form', 10, 5);




//easyreservations_ipn_callback($_POST['invoice'], $_POST['amount']);



function my_gateways_settings(){

}
//add_action('reservations_gateway_settings', 'my_gateways_settings');