<?php

namespace Webflorist\HtmlFactory\Attributes\Traits;

use Webflorist\HtmlFactory\Attributes\AcceptAttribute;

trait AllowsAcceptAttribute
{

    /**
     * Set value of HTML-attribute 'accept'.
     *
     * @param string|\Closure $value
     * @return $this
     */
    public function accept($value)
    {
        $this->attributes->establish(AcceptAttribute::class)->addValue($value);
        return $this;
    }

}