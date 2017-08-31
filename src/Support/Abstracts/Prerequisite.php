<?php
namespace CoreCMF\Core\Support\Abstracts;

use CoreCMF\Core\Support\Contracts\Prerequisite as PrerequisiteContract;

/**
 * Class Prerequisite.
 */
abstract class Prerequisite implements PrerequisiteContract
{
    /**
     * @var array
     */
    protected $messages = [];

    /**
     * Checking prerequisite's rules.
     *
     * @return mixed
     */
    abstract public function check();

    /**
     * Get prerequisite's error message.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
