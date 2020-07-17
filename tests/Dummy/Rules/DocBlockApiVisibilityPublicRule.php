<?php

namespace Klitsche\Dog\Dummy\Rules;

class DocBlockApiVisibilityPublicRule
{
    /**
     * @api
     */
    public int $var;

    /**
     * @api
     */
    protected int $protectedVar;

    /**
     * @api
     */
    private int $privateVar;

    /**
     * @api
     */
    public function func()
    {
    }

    /**
     * @api
     */
    protected function protectedFunc()
    {
    }

    /**
     * @api
     */
    private function privateFunc()
    {
    }
}