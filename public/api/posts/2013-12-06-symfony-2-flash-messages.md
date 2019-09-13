---
id: 2013-12-06-symfony-2-flash-messages
title: Symfony 2.4 flash messages
summary: In Symfony 2.4 the session is not always initialized in a template. You need an extra step to detect this.
draft: false
public: true
published: 2013-12-06T10:00:00+01:00
modified: 2015-02-21T15:39:00+01:00
tags:
    - Symfony 2
---

After upgrading to Symfony 2.4 I got PHPUnit errors. I'm not sure what exactly changed, but the session is not initialized automatically. An extra check is needed:

```php
<ul class="list-unstyled container js-notifications">
{% if app.session and app.session.flashbag %}
    {% for label, flashes in app.session.flashbag.all %}
        {% for flash in flashes %}
            <li class="alert alert-{{ label }} alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ flash }}
            </li>
        {% endfor %}
    {% endfor %}
{% endif %}
{% block notifications %}{% endblock notifications %}
</ul>
```

In case you are wondering what this code does: It shows the current messages in the flashbag and formats it in Bootstrap style. I've placed the code in the default layout view template.

The `js-notifications` class is there so I can add notifications generated with JavaScript. I always prefix JavaScript only classes with `js`. This way I know there is no css class for it and it's used by JavaScript only.
