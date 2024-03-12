<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

class Mark implements JsonConvertible
{
    use JsonStringConvertible;

    public const OBJECT = 'mark';

    public string $type;

    public \stdClass $data;

    public function __construct(string $type, ?\stdClass $data = null)
    {
        $this->type = $type;
        $this->data = $data ?? new \stdClass();
    }

    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'type' => $this->type,
            'data' => $this->data,
        ];
    }

    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            $object->type,
            $object->data
        );
    }
}
