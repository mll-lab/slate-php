<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

class Document extends TextContainingNode
{
    public const OBJECT = 'document';

    /** @var array<Block> */
    public array $nodes;

    public \stdClass $data;

    /** @param array<Block> $nodes */
    public function __construct(array $nodes = [], ?\stdClass $data = null)
    {
        $this->nodes = $nodes;
        $this->data = $data ?? new \stdClass();
    }

    protected function computeTextProperty(): string
    {
        return array_reduce(
            $this->nodes,
            fn (string $text, Block $block): string => $text . $block->text,
            ''
        );
    }

    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'data' => $this->data,
            'nodes' => $this->nodes,
        ];
    }

    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            array_map(
                fn (\stdClass $block): Block => Block::jsonDeserialize($block),
                $object->nodes
            ),
            $object->data
        );
    }
}
