<?php

declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Icon helper
 */
class IconHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];

    public function checkNoCircle()
    {
        return "<svg class=\"w-[15px] h-[15px] text-gray-800 dark:text-white\" aria-hidden=\"true\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\">
            <path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"m5 12 4.7 4.5 9.3-9\"/>
        </svg>";
    }

    public function checkCircle()
    {
        return "<svg class=\"w-[15px] h-[15px] text-gray-800 dark:text-white\" aria-hidden=\"true\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" viewBox=\"0 0 24 24\">
            <path fill-rule=\"evenodd\" d=\"M2 12a10 10 0 1 1 20 0 10 10 0 0 1-20 0Zm13.7-1.3a1 1 0 0 0-1.4-1.4L11 12.6l-1.8-1.8a1 1 0 0 0-1.4 1.4l2.5 2.5c.4.4 1 .4 1.4 0l4-4Z\" clip-rule=\"evenodd\"/>
        </svg>";
    }

    

    public function checkAndCross(bool $flag, array $options)
    {

        if ($flag && $options['circle'] === false) {
            return $this->checkNoCircle();
        }
    }
}
