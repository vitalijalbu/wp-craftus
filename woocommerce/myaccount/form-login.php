<?php
/**
 * My Account Login/Register — custom UI override.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @version 9.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');
?>

<div id="customer_login" class="wc-auth-grid">

  <section class="wc-auth-card wc-auth-card--login" aria-labelledby="wc-login-title">
    <p class="wc-auth-eyebrow"><?php esc_html_e('Bentornato', 'sage'); ?></p>
    <h2 id="wc-login-title" class="wc-auth-title"><?php esc_html_e('Accedi', 'sage'); ?></h2>
    <p class="wc-auth-subtitle"><?php esc_html_e('Accedi al tuo account per ordini, wishlist e indirizzi.', 'sage'); ?></p>

    <form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>
      <?php do_action('woocommerce_login_form_start'); ?>

      <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="username"><?php esc_html_e('Nome utente o indirizzo email', 'sage'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Richiesto', 'sage'); ?></span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
      </p>

      <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="password"><?php esc_html_e('Password', 'sage'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Richiesto', 'sage'); ?></span></label>
        <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
      </p>

      <?php do_action('woocommerce_login_form'); ?>

      <p class="form-row wc-auth-actions">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
          <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
          <span><?php esc_html_e('Ricordami', 'sage'); ?></span>
        </label>
        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e('Accedi', 'sage'); ?>"><?php esc_html_e('Accedi', 'sage'); ?></button>
      </p>

      <p class="woocommerce-LostPassword lost_password">
        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Password dimenticata?', 'sage'); ?></a>
      </p>

      <?php do_action('woocommerce_login_form_end'); ?>
    </form>
  </section>

  <section class="wc-auth-card wc-auth-card--register" aria-labelledby="wc-register-title">
    <p class="wc-auth-eyebrow"><?php esc_html_e('Nuovo cliente', 'sage'); ?></p>
    <h2 id="wc-register-title" class="wc-auth-title"><?php esc_html_e('Crea account', 'sage'); ?></h2>
    <p class="wc-auth-subtitle"><?php esc_html_e('Registrati per checkout più veloce e storico ordini completo.', 'sage'); ?></p>

    <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>
      <?php do_action('woocommerce_register_form_start'); ?>

      <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_username"><?php esc_html_e('Nome utente', 'sage'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Richiesto', 'sage'); ?></span></label>
          <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo !empty($_POST['username']) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
        </p>
      <?php endif; ?>

      <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_email"><?php esc_html_e('Email', 'sage'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Richiesto', 'sage'); ?></span></label>
        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo !empty($_POST['email']) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" />
      </p>

      <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_password"><?php esc_html_e('Password', 'sage'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Richiesto', 'sage'); ?></span></label>
          <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
        </p>
      <?php else : ?>
        <p class="wc-auth-note"><?php esc_html_e('Ti invieremo una email per impostare la password.', 'sage'); ?></p>
      <?php endif; ?>

      <?php do_action('woocommerce_register_form'); ?>

      <p class="woocommerce-form-row form-row">
        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
        <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Registrati', 'sage'); ?>"><?php esc_html_e('Registrati', 'sage'); ?></button>
      </p>

      <?php do_action('woocommerce_register_form_end'); ?>
    </form>
  </section>

</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
