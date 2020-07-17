<?php

namespace Klitsche\Dog\Dummy\Rules;

/**
 * @author
 */
class DocBlockAuthorEmailRule
{
    /**
     * @author valid name <valid.name@example.com>
     * @author emailmissing1
     * @author emailmissing2
     */
    public int $var;

    /**
     * @author emailmissing3
     */
    public function func()
    {
    }
}