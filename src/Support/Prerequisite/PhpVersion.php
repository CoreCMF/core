<?php
namespace CoreCMF\core\Support\Prerequisite;

use CoreCMF\core\Support\Abstracts\Prerequisite;

/**
 * Class PhpVersion.
 */
class PhpVersion extends Prerequisite
{
    /**
     * @var string
     */
    protected $minVersion;

    /**
     * PhpVersion constructor.
     *
     * @param $minVersion
     */
    public function __construct($minVersion)
    {
        $this->minVersion = $minVersion;
    }

    /**
     * Checking prerequisite's rules.
     */
    public function check()
    {
        if (version_compare(PHP_VERSION, $this->minVersion, '<')) {
            $this->messages[] = [
                'type' => 'error',
                'message' => "PHP 版本必须至少为 {$this->minVersion} ，当前运行版本为 " . PHP_VERSION . " ！",
            ];
        } else {
            $this->messages[] = [
                'type' => 'success',
                'message' => "PHP 版本检测通过，当前运行版本为 " . PHP_VERSION . " ！",
            ];
        }
    }
}
