<?php

declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

class SidebarHelper extends Helper
{
    protected array $helpers = ['Html', 'Icon'];

    protected array $_defaultConfig = [
        'class' => 'flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700',
        'classMulti' => 'flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700',
        'spanClassSingle' => "ml-3",
        'spanClassMulti' => 'flex-1 ml-3 text-left whitespace-nowrap'
    ];

    public function singleElementSidebar(array $linkData)
    {
        $icon = $this->Icon->icon($linkData['icon']);
        $title = $linkData['title'];
        $url = [
            'controller' => $linkData['controller'],
            'action' => $linkData['action'],
            'plugin' => $linkData['plugin'] ?? null,
            'params' => $linkData['params'] ?? []
        ];


        $link = $this->Html->link(
            $icon . '<span class="' . $this->_defaultConfig['spanClassSingle'] . '">' . $title . '</span>',
            $url,
            ['escape' => false, 'class' => $this->_defaultConfig['class']]
        );

        return "<li>$link</li>";
    }

    public function parentElementMultiple(array $linkData)
    {
        $icon = $this->Icon->icon($linkData['icon']);
        $title = $linkData['title'];
        $toggleId = h($linkData['toggleId'], ENT_QUOTES, 'UTF-8');

        $html = '<button type="button" class="' . $this->_defaultConfig['classMulti'] . '" aria-controls="' . $toggleId . '" data-collapse-toggle="' . $toggleId . '">';
        $html .= $icon;
        $html .= '<span class="' . $this->_defaultConfig['spanClassMulti'] . '" sidebar-toggle-item>' . $title . '</span>';
        $html .= '</button>';
        return $html;
    }

    public function childElementMultiple(array $linkData)
    {
        $html = '<ul id="' . h($linkData['parentId'], ENT_QUOTES, 'UTF-8') . '" class="hidden py-2 space-y-2">';
        foreach ($linkData['children'] as $child) {
            $url = [
                'controller' => $child['controller'],
                'action' => $child['action'],
                'plugin' => $child['plugin'] ?? null,
                'params' => $child['params'] ?? []
            ];
            $html .= '<li>';
            $html .= $this->Html->link(
                $child['title'],
                $url,
                [
                    'class' => 'flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700'
                ]
            );
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function beginSidebar()
    {
        return <<<EOF
    <aside id="sidebar" class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width" aria-label="Sidebar">
        <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <ul class="pb-2 space-y-2">
    EOF;
    }

    public function endSidebar()
    {
        return "</ul>
        </div>
      </div>
    </div>
  </aside>";
    }
}
