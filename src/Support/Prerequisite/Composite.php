<?php
namespace CoreCMF\Core\Support\Prerequisite;

use CoreCMF\Core\Support\Contracts\Prerequisite;

/**
 * Class Composite.
 */
class Composite implements Prerequisite
{
    /**
     * @var array
     */
    protected $prerequisites = [];

    /**
     * Composite constructor.
     *
     */
    public function __construct()
    {
        foreach (func_get_args() as $prerequisite) {
            $this->prerequisites[] = $prerequisite;
        }
    }

    /**
     * Checking prerequisites's rules.
     *
     * @return mixed
     */
    public function check()
    {
        return array_reduce($this->prerequisites, function ($previous, Prerequisite $prerequisite) {
            return $prerequisite->check() && $previous;
        }, true);
    }

    /**
     * Get prerequisite's error message.
     *
     * @return mixed
     */
    public function getMessages()
    {
        return collect($this->prerequisites)->map(function (Prerequisite $prerequisite) {
            return $prerequisite->getMessages();
        })->reduce('array_merge', []);
    }
}
