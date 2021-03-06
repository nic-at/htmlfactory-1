<?php

namespace Webflorist\HtmlFactory\Attributes\Traits;

use Webflorist\HtmlFactory\Attributes\ReadonlyAttribute;

trait AllowsReadonlyAttribute
{

    /**
     * Set value of HTML-attribute 'readonly'.
     *
     * @param bool|\Closure $readonly
     * @return $this
     */
    public function readonly($readonly = true)
    {
        $this->attributes->establish(ReadonlyAttribute::class)->setValue($readonly);
        return $this;
    }

}