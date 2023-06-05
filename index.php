<?php
/*
Plugin Name: WooCommerce OTP Login
Plugin URI: https://example.com/woocommerce-otp-login/
Description: This plugin allows users to log in to WooCommerce using an OTP (One Time Password).
Version: 1.0
Author: Rahees Ahmed
Author URI: https://github.com/raheesahmed
License: GPLv2 or later
*/

add_action( 'woocommerce_login_form', function() {
  // Get the user's phone number.
  $phone_number = get_user_meta( get_current_user_id(), 'phone_number', true );

  // If the user has a phone number, add an OTP field to the login form.
  if ( $phone_number ) {
    ?>
    <input type="text" name="otp" placeholder="Enter your OTP" />
    <?php
  }
});

add_action( 'woocommerce_login_process', function( $result ) {
  // If the user has submitted an OTP, verify it.
  if ( isset( $_POST['otp'] ) ) {
    $otp = $_POST['otp'];

    // Get the user's phone number.
    $phone_number = get_user_meta( get_current_user_id(), 'phone_number', true );

    // Send an OTP to the user's phone number.
    $otp_service = new \Your\OTP\Service();
    $otp_service->send_otp( $phone_number );

    // Check if the OTP is correct.
    $is_valid_otp = $otp_service->verify_otp( $otp );

    // If the OTP is correct, log the user in.
    if ( $is_valid_otp ) {
      $result = wp_signon( $_POST );
    }
  }
});
