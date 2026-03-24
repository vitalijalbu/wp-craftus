<?php
/**
 * Email Footer — branded.
 *
 * @see     https://woocommerce.com/document/template-structure/
 *
 * @var object $email
 */
defined('ABSPATH') || exit;

$privacy_url = get_privacy_policy_url() ?: home_url('/privacy-policy');
$site_url = home_url('/');
$site_name = get_bloginfo('name');
$address = get_theme_mod('contact_address', '');

do_action('woocommerce_email_footer', $email);
?>
          </td>{{-- /body_content_inner --}}
        </tr>
      </table>{{-- /body_content --}}
    </td>
  </tr>{{-- /template_body --}}

  {{-- Footer --}}
  <tr>
    <td id="template_footer" style="background-color:#0a0a0a;padding:24px 40px;">
      <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
          <td valign="top" align="left">
            <p style="font-family:'Poppins',-apple-system,sans-serif;font-size:12px;color:rgba(255,255,255,0.4);margin:0 0 6px;line-height:1.6;">
              © <?= esc_html(date('Y')) ?> <a href="<?= esc_url($site_url) ?>" style="color:rgba(255,255,255,0.5);text-decoration:none;"><?= esc_html($site_name) ?></a>.
              <?php _e('Tutti i diritti riservati.', 'sage') ?>
            </p>
            <?php if ($address) { ?>
              <p style="font-family:'Poppins',-apple-system,sans-serif;font-size:11px;color:rgba(255,255,255,0.25);margin:0 0 6px;line-height:1.5;">
                <?= nl2br(esc_html($address)) ?>
              </p>
            <?php } ?>
            <p style="font-family:'Poppins',-apple-system,sans-serif;font-size:11px;color:rgba(255,255,255,0.25);margin:0;line-height:1.5;">
              <a href="<?= esc_url($privacy_url) ?>" style="color:rgba(255,255,255,0.4);text-decoration:none;">
                <?php _e('Privacy Policy', 'sage') ?>
              </a>
              &nbsp;·&nbsp;
              <a href="<?= esc_url(home_url('/cookie-policy')) ?>" style="color:rgba(255,255,255,0.4);text-decoration:none;">
                <?php _e('Cookie Policy', 'sage') ?>
              </a>
            </p>
          </td>
          <?php if (has_custom_logo()) { ?>
          <td valign="middle" align="right" style="padding-left:20px;">
            <?php
            $logo_id = get_theme_mod('custom_logo');
              $logo_url = wp_get_attachment_image_url($logo_id, 'thumbnail');
              if ($logo_url) {
                  ?>
              <img src="<?= esc_url($logo_url) ?>" alt="<?= esc_attr($site_name) ?>" style="max-height:32px;width:auto;opacity:0.4;">
            <?php } ?>
          </td>
          <?php } ?>
        </tr>
      </table>
    </td>
  </tr>

</table>{{-- /template_container --}}

</td></tr>
</table>{{-- /wrapper --}}

</body>
</html>
