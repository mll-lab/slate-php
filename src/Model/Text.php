<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

class Text extends TextContainingNode
{
    public const OBJECT = 'text';

    /** @var array<Leaf> */
    public array $leaves;

    /** @param array<Leaf> $leaves */
    public function __construct(array $leaves = [])
    {
        $this->leaves = $leaves;
    }

    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'leaves' => $this->leaves,
        ];
    }

    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            array_map(
                fn (\stdClass $leaf): Leaf => Leaf::jsonDeserialize($leaf),
                $object->leaves
            )
        );
    }

    /** Concatenate all the descendant text nodes of this node. */
    protected function computeTextProperty(): string
    {
        return array_reduce(
            $this->leaves,
            fn (string $text, Leaf $leaf): string => $text . $leaf->text,
            ''
        );
    }
}
