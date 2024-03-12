<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

class Inline extends TextContainingNode
{
    public const OBJECT = 'inline';

    public string $type;

    public \stdClass $data;

    /** @var array<Inline>|array<Text> */
    public array $nodes = [];

    /** @param array<Text>|array<Inline> $nodes */
    public function __construct(string $type, ?array $nodes = null, ?\stdClass $data = null)
    {
        $this->type = $type;
        $this->nodes = $nodes ?? [new Text()];
        $this->data = $data ?? new \stdClass();
    }

    public function computeTextProperty(): string
    {
        return array_reduce(
            $this->nodes,
            fn (string $text, TextContainingNode $node): string => $text . $node->text,
            ''
        );
    }

    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'object' => self::OBJECT,
            'type' => $this->type,
            'data' => $this->data,
            'nodes' => $this->nodes,
        ];
    }

    public static function jsonDeserialize(\stdClass $object): self
    {
        return new self(
            $object->type,
            array_map(
                function (\stdClass $node): TextContainingNode {
                    switch ($node->object) {
                        case self::OBJECT:
                            return self::jsonDeserialize($node);
                        case Text::OBJECT:
                            return Text::jsonDeserialize($node);
                        default:
                            throw new \Exception("Found unknown node of kind {$node->object} when trying to deserialize a Inline");
                    }
                },
                $object->nodes
            ),
            $object->data
        );
    }
}
