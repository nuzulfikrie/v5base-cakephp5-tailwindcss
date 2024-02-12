<?php

namespace App\Exception;

use Cake\Core\Exception\CakeException;

class InvalidParameterException extends CakeException
{

  public function __construct($message, $code = 500, $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
