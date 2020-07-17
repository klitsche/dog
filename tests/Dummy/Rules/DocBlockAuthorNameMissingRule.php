<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @author valid name
 */
class DocBlockAuthorNameMissingRule
{
    /**
     * @author valid name
     * @author
     * @author
     */
    public int $var;

    /**
     * @author
     */
    public function func()
    {
    }
}