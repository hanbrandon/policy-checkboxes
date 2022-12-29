<?php

/**
 * Plugin Name: Policy Checkboxes
 * Description: Adds checkboxes for privacy policy and terms and conditions to the WooCommerce registration form and allows users to set the pages in the WordPress dashboard.
 * Version: 1.0
 * Author: Sang Hyun Han
 * License: GPL2
 */

function woocommerce_policy_checkboxes() {
  // Get the page IDs for the privacy policy and terms and conditions pages
  $privacy_policy_page_id = get_option( 'woocommerce_privacy_policy_page', 0 );
  $terms_and_conditions_page_id = get_option( 'woocommerce_terms_and_conditions_page', 0 );

  // Get the URLs for the privacy policy and terms and conditions pages
  $privacy_policy_url = get_permalink( $privacy_policy_page_id );
  $terms_and_conditions_url = get_permalink( $terms_and_conditions_page_id );

  // Add the checkboxes to the WooCommerce registration form
  ?>
  <p class="form-row form-row-wide">
    <label for="policy_checkbox">
      <input type="checkbox" name="policy_checkbox" id="policy_checkbox" required>
      I have read and agree to the <a href="<?php echo esc_url( $privacy_policy_url ); ?>" target="_blank">privacy policy</a> and <a href="<?php echo esc_url( $terms_and_conditions_url ); ?>" target="_blank">terms and conditions</a>.
    </label>
  </p>
  <?php
}
add_action( 'woocommerce_register_form', 'woocommerce_policy_checkboxes' );
function woocommerce_policy_settings_init() {
  // Register a new setting for the privacy policy and terms and conditions pages
  register_setting( 'general', 'woocommerce_privacy_policy_page', array(
    'type' => 'integer',
    'description' => 'The page to use as the privacy policy.',
    'sanitize_callback' => 'absint',
  ) );
  register_setting( 'general', 'woocommerce_terms_and_conditions_page', array(
    'type' => 'integer',
    'description' => 'The page to use as the terms and conditions.',
    'sanitize_callback' => 'absint',
  ) );

  // Add a section to the General Settings page to display the privacy policy and terms and conditions settings
  add_settings_section( 'woocommerce_policy_settings', 'Privacy Policy and Terms and Conditions', 'woocommerce_policy_settings_callback', 'general' );

  // Add fields to the section to allow users to select their privacy policy and terms and conditions pages
  add_settings_field( 'woocommerce_privacy_policy_page', 'Privacy Policy Page', 'woocommerce_privacy_policy_page_callback', 'general', 'woocommerce_policy_settings' );
  add_settings_field( 'woocommerce_terms_and_conditions_page', 'Terms and Conditions Page', 'woocommerce_terms_and_conditions_page_callback', 'general', 'woocommerce_policy_settings' );
}
add_action( 'admin_init', 'woocommerce_policy_settings_init' );


// Callback function for the settings section
function woocommerce_policy_settings_callback() {
  echo '<p>Select the pages to use as the privacy policy and terms and conditions for your WooCommerce store.</p>';
}

// Callback functions for the settings fields
function woocommerce_privacy_policy_page_callback() {
  $page_id = get_option( 'woocommerce_privacy_policy_page', 0 );
  wp_dropdown_pages( array(
    'name' => 'woocommerce_privacy_policy_page',
    'selected' => $page_id,
    'show_option_none' => '- Select -',
  ) );
}

function woocommerce_terms_and_conditions_page_callback() {
  $page_id = get_option( 'woocommerce_terms_and_conditions_page', 0 );
  wp_dropdown_pages( array(
    'name' => 'woocommerce_terms_and_conditions_page',
    'selected' => $page_id,
    'show_option_none' => '- Select -',
  ) );
}

