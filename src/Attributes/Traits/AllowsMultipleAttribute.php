<?php

namespace Webflorist\HtmlFactory\Attributes\Traits;

use Webflorist\HtmlFactory\Attributes\MultipleAttribute;

trait AllowsMultipleAttribute
{

    /**
     * Set value of HTML-attribute 'multiple'.
     *
     * The multiple attribute is a boolean attribute.
     * When present, it specifies that the user is allowed to enter/select more than one value.
     *
     * @param bool|\Closure $multiple
     * @return $this
     */
    public function multiple($multiple = true)
    {
        $this->attributes->establish(MultipleAttribute::class)->setValue($multiple);
        return $this;
    }

}