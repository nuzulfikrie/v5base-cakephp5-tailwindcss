<?php
declare(strict_types=1);

namespace App\Test\TestCase\View\Helper;

use App\View\Helper\SidebarHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\SidebarHelper Test Case
 */
class SidebarHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\View\Helper\SidebarHelper
     */
    protected $Sidebar;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Sidebar = new SidebarHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Sidebar);

        parent::tearDown();
    }
}
