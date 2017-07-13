<?php
namespace CoreCMF\core\Support\Contracts;

/**
 * Interface PrerequisiteContract.
 */
interface Prerequisite
{
    /**
     * Checking prerequisite's rules.
     *
     * @return mixed
     */
    public function check();

    /**
     * Get prerequisite's error message.
     *
     * @return mixed
     */
    public function getMessages();
}
