<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

class Leaf extends TextContainingNode
{
    public const OBJECT = 'leaf';

    public string $text;

    /** @var array<Mark> */
    public array $marks;

    /** @param array<Mark> $marks */
    public function __construct(string $text, array $marks = [])
    {
        $this->text = $text;
        $this->marks = $marks;
    }

    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'text' => $this->text,
            'marks' => $this->marks,
        ];
    }

    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            $object->text,
            array_map(
                fn (\stdClass $mark): Mark => Mark::jsonDeserialize($mark),
                $object->marks
            )
        );
    }

    /** We are finally there, just return the text. */
    protected function computeTextProperty(): string
    {
        return $this->text;
    }
}
