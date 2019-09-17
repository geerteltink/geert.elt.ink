---
id: 2015-06-04-symfony-shibboleth-login-the-easy-way
title: Symfony 2.6 Shibboleth Login - The Easy Way
summary: Since Symfony 2.6 Shibboleth logins can be added easily with the remote_user security option.
date: 2015-06-04
tags:
    - Symfony
    - Shibboleth
    - security
---

There was a time we had to write complicated-hard-to-maintain Shibboleth bundles to get it working with Symfony. I did as well back in the Symfony 2.4 days. Fortunately since Symfony 2.6 there is a new security firewall option called [remote_user](http://symfony.com/doc/current/cookbook/security/pre_authenticated.html#remote-user-based-authentication). The REMOTE_USER variable passed by the http server is actually a [standard](https://www.ietf.org/rfc/rfc3875).

> A lot of authentication modules, like `auth_kerb` for Apache provide the username using the REMOTE_USER environment variable. This variable can be trusted by the application since the authentication happened before the request reached it.

This principle is also used by Shibboleth, at least in my situation. However the passed variable names was called differently, but it still passed the username in it. Luckily Symfony has even a configuration for that. So with only a few lines of code, you replace your home-brewed-insecure bundle for well-tested-built-in Symfony option. The built-in `form_login` and `logout` options are used to redirect to the Shibboleth endpoints where the actual login and logout takes place.

```yaml
// app/config/security.yml
security:
    // ...

    firewalls:
        shibboleth_firewall:
            pattern:   ^/

            remote_user:
                provider: main
                # Rename this if Shibboleth uses another var
                user: REMOTE_USER

            # Use the form login to redirect to the Shibboleth login endpoint
            form_login:
                login_path: http://example.com/login-endpoint

            # Use the logout to redirect to the Shibboleth logout endpoint
            logout:
                path: /logout
                target: http://example.com/logout-endpoint
                invalidate_session: true
```

## Testing

To test this in PHPUnit:

```php
<?php

$client = static::createClient();
$this->client->request('GET', '/secure', [], [], [
    'REMOTE_USER' => 'admin'
]);
```

If you want to simulate it during testing set the REMOTE_USER in `.htaccess`:

```apacheconf
# Shibboleth user login. Use for testing only!
SetEnv REMOTE_USER admin
```
