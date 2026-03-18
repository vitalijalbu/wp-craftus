# 12 — REST API & Integrazioni

## Endpoint newsletter

Definito in `app/filters.php`.

```
POST /wp-json/4zampe/v1/newsletter
Content-Type: application/json
X-WP-Nonce: <nonce wp_rest>

{ "email": "utente@esempio.it" }
```

### Risposta successo

```json
{ "success": true, "message": "Iscrizione effettuata. Grazie!" }
```

### Risposta errore (422)

```json
{ "code": "invalid_email", "message": "Indirizzo email non valido." }
```

### Hook di integrazione ESP

L'endpoint **non salva l'email** direttamente — lancia un'action WordPress:

```php
do_action('4zampe_newsletter_subscribe', $email);
```

Aggancia il tuo ESP (Mailchimp, Klaviyo, Brevo…) a questo hook:

```php
// In app/filters.php o in un file dedicato integrations.php

add_action('4zampe_newsletter_subscribe', function (string $email): void {
    // Mailchimp
    $mc = new \MailchimpMarketing\ApiClient();
    $mc->setConfig(['apiKey' => getenv('MAILCHIMP_API_KEY'), 'server' => 'us1']);
    $mc->lists->addListMember(getenv('MAILCHIMP_LIST_ID'), [
        'email_address' => $email,
        'status'        => 'subscribed',
    ]);
});
```

```php
// Klaviyo
add_action('4zampe_newsletter_subscribe', function (string $email): void {
    wp_remote_post('https://a.klaviyo.com/api/v2/list/LIST_ID/subscribe', [
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => wp_json_encode([
            'api_key' => getenv('KLAVIYO_API_KEY'),
            'profiles' => [['email' => $email]],
        ]),
    ]);
});
```

```php
// Salva solo nel DB (opzione minimal)
add_action('4zampe_newsletter_subscribe', function (string $email): void {
    $subscribers = get_option('theme_newsletter_subscribers', []);
    if (!in_array($email, $subscribers, true)) {
        $subscribers[] = $email;
        update_option('theme_newsletter_subscribers', $subscribers);
    }
});
```

---

## Aggiungere nuovi endpoint REST

```php
// In app/filters.php
add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/contact', [
        'methods'             => 'POST',
        'callback'            => __NAMESPACE__ . '\\handle_contact_form',
        'permission_callback' => '__return_true',
        'args'                => [
            'name'    => ['required' => true, 'sanitize_callback' => 'sanitize_text_field'],
            'email'   => ['required' => true, 'sanitize_callback' => 'sanitize_email',
                          'validate_callback' => fn($v) => is_email($v)],
            'message' => ['required' => true, 'sanitize_callback' => 'sanitize_textarea_field'],
        ],
    ]);
});

function handle_contact_form(\WP_REST_Request $request) {
    $name    = $request->get_param('name');
    $email   = $request->get_param('email');
    $message = $request->get_param('message');

    // Invia email
    wp_mail(
        get_option('admin_email'),
        "Nuovo contatto da {$name}",
        $message,
        ["Reply-To: {$name} <{$email}>"]
    );

    return rest_ensure_response(['success' => true]);
}
```

### Chiamata fetch dal frontend

```js
const nonce = window.wpApiSettings?.nonce ?? ''

await fetch('/wp-json/theme/v1/contact', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': nonce,
    },
    body: JSON.stringify({ name, email, message }),
})
```

### Rendere il nonce disponibile in JS

```php
// In app/setup.php o functions.php
add_action('wp_enqueue_scripts', function () {
    wp_localize_script('theme-app', 'wpApiSettings', [
        'nonce'   => wp_create_nonce('wp_rest'),
        'restUrl' => rest_url(),
    ]);
});
```

Oppure con Vite (senza dipendere dall'handle):

```php
add_action('wp_head', function () {
    $data = json_encode([
        'nonce'   => wp_create_nonce('wp_rest'),
        'restUrl' => rest_url(),
    ]);
    echo "<script>window.wpApiSettings = {$data};</script>\n";
}, 5);
```

---

## AJAX WordPress classico (alternativa a REST)

Per azioni admin-ajax:

```php
// In app/filters.php
add_action('wp_ajax_my_action',        __NAMESPACE__ . '\\my_ajax_handler');
add_action('wp_ajax_nopriv_my_action', __NAMESPACE__ . '\\my_ajax_handler');

function my_ajax_handler(): void {
    check_ajax_referer('my_nonce', 'nonce');
    $data = sanitize_text_field($_POST['data'] ?? '');
    wp_send_json_success(['result' => $data]);
}
```

Chiamata JS:
```js
const formData = new FormData()
formData.append('action', 'my_action')
formData.append('nonce', window.ajaxNonce)
formData.append('data', 'valore')

fetch(window.ajaxUrl, { method: 'POST', body: formData })
    .then(r => r.json())
    .then(d => console.log(d))
```

---

## Proteggere endpoint con autenticazione

```php
register_rest_route('theme/v1', '/admin-only', [
    'methods'             => 'GET',
    'callback'            => fn() => ['data' => 'secret'],
    'permission_callback' => function () {
        return current_user_can('manage_options');
    },
]);
```

Per accesso solo da utenti loggati:
```php
'permission_callback' => 'is_user_logged_in',
```

---

## Integrazioni plugin consigliate

| Integrazione | Package / Plugin | Hook da usare |
|-------------|-----------------|---------------|
| Mailchimp | `mailchimp/marketing` (Composer) | `4zampe_newsletter_subscribe` |
| Klaviyo | HTTP diretto | `4zampe_newsletter_subscribe` |
| Brevo (ex Sendinblue) | HTTP diretto | `4zampe_newsletter_subscribe` |
| ConvertKit | HTTP API v3 | `4zampe_newsletter_subscribe` |
| Contact Form 7 | plugin CF7 | `wpcf7_mail_sent` |
| Gravity Forms | plugin GF | `gform_after_submission` |
| Google Analytics | script in `wp_head` | — |
| Pixel Meta | plugin o script | `wp_head` / `wp_footer` |
