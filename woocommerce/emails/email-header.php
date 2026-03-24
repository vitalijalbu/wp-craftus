<?php
/**
 * Email Header — branded.
 *
 * @see     https://woocommerce.com/document/template-structure/
 *
 * @var string $email_heading
 * @var bool $plain_text
 * @var object $email
 */
defined('ABSPATH') || exit;

do_action('woocommerce_email_header', $email_heading, $email);
?>
<!DOCTYPE html>
<html lang="<?= esc_attr(get_bloginfo('language')) ?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc_html(get_bloginfo('name')) ?></title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

<table id="wrapper" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td align="center" valign="top">

<table id="template_container" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;">

  {{-- Header --}}
  <tr>
    <td id="template_header" align="left" valign="top" style="background-color:#0a0a0a;padding:32px 40px;">

      <?php if (has_custom_logo()) { ?>
        <div id="template_header_image">
          <?php
          $logo_id = get_theme_mod('custom_logo');
          $logo_url = wp_get_attachment_image_url($logo_id, 'medium');
          if ($logo_url) {
              ?>
            <img src="<?= esc_url($logo_url) ?>" alt="<?= esc_attr(get_bloginfo('name')) ?>" style="max-height:48px;width:auto;display:block;">
          <?php } ?>
        </div>
      <?php } else { ?>
        <h1 style="color:#ffffff;font-family:'Inter',-apple-system,sans-serif;font-size:20px;font-weight:300;letter-spacing:0.1em;text-transform:uppercase;margin:0;">
          <?= esc_html(get_bloginfo('name')) ?>
        </h1>
      <?php } ?>

    </td>
  </tr>

  {{-- Subject line band --}}
  <?php if ($email_heading) { ?>
  <tr>
    <td style="background-color:#0074C7;padding:18px 40px;">
      <h2 style="color:#ffffff;font-family:'Inter',-apple-system,sans-serif;font-size:16px;font-weight:400;letter-spacing:0.02em;margin:0;">
        <?= wp_kses_post($email_heading) ?>
      </h2>
    </td>
  </tr>
  <?php } ?>

  {{-- Body --}}
  <tr>
    <td id="template_body" valign="top">
      <table id="body_content" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
          <td id="body_content_inner" align="left" valign="top" style="padding:40px;">
