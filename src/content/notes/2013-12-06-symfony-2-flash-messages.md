---
title: 'Symfony 2.4 flash messages'
description: 'In Symfony 2.4 the session is not always initialized in a template. You need an extra step to detect this.'
slug: symfony-2-flash-messages
pubDate: 2013-12-06
tags:
  - Symfony 2
---

After upgrading to Symfony 2.4 I got PHPUnit errors. I'm not sure what exactly changed, but the session is not initialized automatically. An extra check is needed:

```php
<ul class="list-unstyled container js-notifications">
&#123;% if app.session and app.session.flashbag %&#125;
    &#123;% for label, flashes in app.session.flashbag.all %&#125;
        &#123;% for flash in flashes %&#125;
            <li class="alert alert-&#123;&#123; label &#125;&#125; alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                &#123;&#123; flash &#125;&#125;
            </li>
        &#123;% endfor %&#125;
    &#123;% endfor %&#125;
&#123;% endif %&#125;
&#123;% block notifications %&#125;&#123;% endblock notifications %&#125;
</ul>
```

In case you are wondering what this code does: It shows the current messages in the flashbag and formats it in Bootstrap style. I've placed the code in the default layout view template.

The `js-notifications` class is there so I can add notifications generated with JavaScript. I always prefix JavaScript only classes with `js`. This way I know there is no css class for it and it's used by JavaScript only.
