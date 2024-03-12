<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

trait JsonStringConvertible
{
    public static function fromJSON(string $json): self
    {
        $object = \Safe\json_decode($json);
        if (! $object instanceof \stdClass) {
            throw new \InvalidArgumentException("Expected JSON object, got: {$json}.");
        }

        return static::jsonDeserialize($object);
    }

    public function toJSON(int $flags = 0): string
    {
        return \Safe\json_encode(
            static::jsonSerialize(),
            $flags
        );
    }

    abstract public static function jsonDeserialize(\stdClass $object): self;

    abstract public function jsonSerialize(): \stdClass;
}
