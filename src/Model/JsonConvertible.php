<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

interface JsonConvertible extends \JsonSerializable
{
    public static function jsonDeserialize(\stdClass $object): self;

    public function jsonSerialize(): \stdClass;
}
