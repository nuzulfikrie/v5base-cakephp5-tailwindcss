<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->disableAutoLayout();

$checkConnection = function (string $name) {
  $error = null;
  $connected = false;
  try {
    ConnectionManager::get($name)->getDriver()->connect();
    // No exception means success
    $connected = true;
  } catch (Exception $connectionError) {
    $error = $connectionError->getMessage();
    if (method_exists($connectionError, 'getAttributes')) {
      $attributes = $connectionError->getAttributes();
      if (isset($attributes['message'])) {
        $error .= '<br />' . $attributes['message'];
      }
    }
    if ($name === 'debug_kit') {
      $error = 'Try adding your current <b>top level domain</b> to the
                <a href="https://book.cakephp.org/debugkit/5/en/index.html#configuration" target="_blank">DebugKit.safeTld</a>
            config and reload.';
      if (!in_array('sqlite', \PDO::getAvailableDrivers())) {
        $error .= '<br />You need to install the PHP extension <code>pdo_sqlite</code> so DebugKit can work properly.';
      }
    }
  }

  return compact('connected', 'error');
};

if (!Configure::read('debug')) :
  throw new NotFoundException(
    'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
  );
endif;

?>
<!DOCTYPE html>
<html>

<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    CakePHP: the rapid development PHP framework:
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->Html->meta('icon') ?>

  <!-- Tailwind CSS CDN for simplicity -->

  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->Html->css('style.css') ?>
  <?= $this->fetch('script') ?>
</head>


<body class="bg-gray-100 text-gray-900">
  <div class="bg-white shadow">
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <a href="https://cakephp.org/" target="_blank" rel="noopener" class="flex items-center">
          <img alt="CakePHP" src="https://cakephp.org/v2/img/logos/CakePHP_Logo.svg" width="150" class="mr-3">
          <span class="text-xl font-bold">Welcome to CakePHP <?= h(Configure::version()) ?> Chiffon (🍰)</span>
        </a>
      </div>
    </div>
  </div>

  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Environment Check -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="font-bold text-lg mb-2">Environment</h4>
        Your PHP version checks and other environment checks here...
        <!-- Use text-green-500 for success messages and text-red-500 for error messages -->
        <?php if (version_compare(PHP_VERSION, '8.1.0', '>=')) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">Your version of PHP is 8.1.0 or higher (detected <?= PHP_VERSION ?>).</p>
        <?php else : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
          </svg>
          <p class="text-red-500">Your version of PHP is too low. You need PHP 8.1.0 or higher to use CakePHP (detected <?= PHP_VERSION ?>).</p>
        <?php endif; ?>

        <?php if (extension_loaded('mbstring')) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">Your version of PHP has the mbstring extension loaded.</p>
        <?php else : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
          </svg>
          <p class="text-red-500">Your version of PHP does NOT have the mbstring extension loaded.</p>
        <?php endif; ?>

        <?php if (extension_loaded('openssl')) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">Your version of PHP has the openssl extension loaded.</p>
        <?php elseif (extension_loaded('mcrypt')) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">Your version of PHP has the mcrypt extension loaded.</p>
        <?php else : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
          </svg>
          <p class="text-red-500">Your version of PHP does NOT have the openssl or mcrypt extension loaded.</p>
        <?php endif; ?>

        <?php if (extension_loaded('intl')) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">Your version of PHP has the mcrypt extension loaded.</p>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">Your version of PHP has the intl extension loaded.</p>
        <?php else : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
          </svg>
          <p class="text-red-500">Your version of PHP does NOT have the intl extension loaded.</p>
        <?php endif; ?>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="font-bold text-lg mb-2">Filesystem</h4>
        <?php if (is_writable(TMP)) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">Your tmp directory is writable.</p>
        <?php else : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
          </svg>
          <p class="text-red-500">Your tmp directory is NOT writable.</p>
        <?php endif; ?>

        <?php if (is_writable(LOGS)) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">Your logs directory is writable.</p>
        <?php else : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
          </svg>
          <p class="text-red-500">Your logs directory is NOT writable.</p>
        <?php endif; ?>

        <?php $settings = Cache::getConfig('_cake_core_'); ?>
        <?php if (!empty($settings)) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">The <em><?= h($settings['className']) ?></em> is being used for core caching. To change the config edit config/app.php</p>
        <?php else : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
          </svg>
          <p class="text-red-500">Your cache is NOT working. Please check the settings in config/app.php</p>
        <?php endif; ?>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="font-bold text-lg mb-2">Database</h4>
        Database connection checks here...
        <?php
        $result = $checkConnection('default');
        ?>
        <ul>
          <?php if ($result['connected']) : ?>
            <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
            </svg>
            <p class="text-green-500">CakePHP is able to connect to the database.</p>
          <?php else : ?>
            <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
            </svg>
            <p class="text-red-500">CakePHP is NOT able to connect to the database.<br /><?= h($result['error']) ?></p>
          <?php endif; ?>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h4 class="font-bold text-lg mb-2">DebugKit</h4>
        DebugKit status checks here...
        <?php if (Plugin::isLoaded('DebugKit')) : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9" />
          </svg>
          <p class="text-green-500">DebugKit is loaded.</p>
          <?php
          $result = $checkConnection('debug_kit');
          ?>
          <?php if ($result['connected']) : ?>
            <p class="text-green-500">DebugKit can connect to the database.</p>
          <?php else : ?>
            <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
            </svg>
            <p class="text-red-500">There are configuration problems present which need to be fixed:<br /><?= $result['error'] ?></p>
          <?php endif; ?>
        <?php else : ?>
          <svg class="w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M9 14.3H5a2 2 0 0 1-1.6-.9 2 2 0 0 1-.3-1.8l2.4-7.2C5.8 3.5 6 3 7.4 3c2 0 4.2.7 6.1 1.3l1.4.4v9.8a32 32 0 0 0-4.2 5.5c-.1.4-.5.7-.9.9a1.7 1.7 0 0 1-2.1-.7c-.2-.4-.3-.8-.3-1.3L9 14.3Zm10.8-.3H17V6a2 2 0 1 1 4 0v6.8c0 .7-.5 1.2-1.2 1.2Z" clip-rule="evenodd" />
          </svg>
          <p class="text-red-500">DebugKit is <strong>not</strong> loaded.</p>
        <?php endif; ?>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">

        <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Getting Started</h5>
        <a target="_blank" rel="noopener" href="https://book.cakephp.org/5/en/" class="inline-flex items-center text-blue-600 hover:underline">CakePHP Documentation</a>
        <a target="_blank" rel="noopener" href="https://book.cakephp.org/5/en/tutorials-and-examples/cms/installation.html" class="inline-flex items-center text-blue-600 hover:underline">The 20 min CMS Tutorial</a>


      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Help and Bug Reports</h5>
        <a target="_blank" rel="noopener" href="https://slack-invite.cakephp.org/" class="inline-flex items-center text-blue-600 hover:underline">Slack</a>
        <a target="_blank" rel="noopener" href="https://github.com/cakephp/cakephp/issues" class="inline-flex items-center text-blue-600 hover:underline">CakePHP Issues</a>
        <a target="_blank" rel="noopener" href="https://discourse.cakephp.org/" class="inline-flex items-center text-blue-600 hover:underline">CakePHP Forum</a>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Docs and Downloads</h5>

        <a target="_blank" rel="noopener" href="https://api.cakephp.org/" class="inline-flex items-center text-blue-600 hover:underline">CakePHP API</a>
        <a target="_blank" rel="noopener" href="https://bakery.cakephp.org">The Bakery</a>
        <a target="_blank" rel="noopener" href="https://book.cakephp.org/5/en/" class="inline-flex items-center text-blue-600 hover:underline" class="inline-flex items-center text-blue-600 hover:underline">CakePHP Documentation</a>
        <a target="_blank" rel="noopener" href="https://plugins.cakephp.org" class="inline-flex items-center text-blue-600 hover:underline">CakePHP plugins repo</a>
        <a target="_blank" rel="noopener" href="https://github.com/cakephp/" class="inline-flex items-center text-blue-600 hover:underline">CakePHP Code</a>
        <a target="_blank" rel="noopener" href="https://github.com/FriendsOfCake/awesome-cakephp">CakePHP Awesome List</a>
        <a target="_blank" rel="noopener" href="https://www.cakephp.org" class="inline-flex items-center text-blue-600 hover:underline" class="inline-flex items-center text-blue-600 hover:underline">CakePHP</a>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Training and Certification</h5>
        <a target="_blank" rel="noopener" href="https://cakefoundation.org/" class="inline-flex items-center text-blue-600 hover:underline">Cake Software Foundation</a>
        <a target="_blank" rel="noopener" href="https://training.cakephp.org/">CakePHP Training</a>
      </div>
    </div>
  </div>

  <?= $this->Html->script('flowbite.min') ?>
</body>

</html>