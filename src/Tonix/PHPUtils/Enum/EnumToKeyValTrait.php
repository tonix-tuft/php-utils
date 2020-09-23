<?php

namespace Tonix\PHPUtils\Enum;

trait EnumToKeyValTrait {
  public static function toKeyVal() {
    $reflectionClass = new ReflectionClass(get_called_class());
    return $reflectionClass->getConstants();
  }
}
