<?php

declare(strict_types=1);

namespace App\View\Helper;

use App\Exception\InvalidParameterException;
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

    public function dashboardSvg()
    {
        return "<svg class=\"w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\">
            <path d=\"M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z\"></path>
            <path d=\"M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z\"></path>
        </svg>";
    }

    public function icon(string $iconName)
    {

        if (!$iconName) {
            throw new InvalidParameterException(__('Icon name is required'));
        }

        if ($iconName === 'dashboard') {
            return $this->dashboardSvg();
        }

        if ($iconName === 'CheckCircle') {
            return $this->checkCircle();
        }

        if ($iconName === 'NoCircle') {
            return $this->checkNoCircle();
        }
    }

    public function checkAndCross(bool $flag, array $options)
    {

        if ($flag === true && $options['circle'] === false) {
            return $this->checkNoCircle();
        }
    }
}
