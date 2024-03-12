<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

class Value implements JsonConvertible
{
    use JsonStringConvertible;

    public const OBJECT = 'value';

    public Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            Document::jsonDeserialize($object->document)
        );
    }

    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'document' => $this->document,
        ];
    }
}
