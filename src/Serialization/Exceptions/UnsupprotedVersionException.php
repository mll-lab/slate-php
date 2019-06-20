<?php
namespace Prezly\Slate\Serialization\Exceptions;

use InvalidArgumentException;

class UnsupprotedVersionException extends InvalidArgumentException
{
    public function __construct(string $version)
    {
        $message = "Unsupported serialization version requested: {$version}";

        parent::__construct($message, 0, null);
    }
}
