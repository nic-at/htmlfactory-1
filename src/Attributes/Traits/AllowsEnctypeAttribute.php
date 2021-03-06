<?php

namespace Webflorist\HtmlFactory\Attributes\Traits;

use Webflorist\HtmlFactory\Attributes\EnctypeAttribute;

trait AllowsEnctypeAttribute
{

    /**
     * Set value of HTML-attribute 'enctype'.
     *
     * @param string|\Closure $enctype
     * @return $this
     */
    public function enctype($enctype)
    {
        $this->attributes->establish(EnctypeAttribute::class)->setValue($enctype);
        return $this;
    }

}