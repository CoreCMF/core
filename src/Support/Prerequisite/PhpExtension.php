<?php
namespace CoreCMF\Core\Support\Prerequisite;

use CoreCMF\Core\Support\Abstracts\Prerequisite;

/**
 * Class PhpExtension.
 */
class PhpExtension extends Prerequisite
{
    /**
     * @var array
     */
    protected $extensions;

    /**
     * PhpExtension constructor.
     *
     * @param array $extensions
     */
    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Checking prerequisite's rules.
     */
    public function check()
    {
        $hasInstalled = collect();
        $notInstalled = collect();
        foreach ($this->extensions as $extension) {
            if (!extension_loaded($extension)) {
                $notInstalled->push($extension);

            } else {
                $hasInstalled->push($extension);

            }
        }
        $hasInstalled->count() && $this->messages[] = [
            'type' => 'success',
            'message' => "PHP 扩展 '" . $hasInstalled->implode("', '") . "' 已经安装。",
        ];
        $notInstalled->count() && $this->messages[] = [
            'type' => 'error',
            'message' => "必须安装 PHP 扩展 '" . $notInstalled->implode("', '") . "'！",
        ];
    }
}
