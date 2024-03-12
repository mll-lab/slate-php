<?php declare(strict_types=1);

namespace Prezly\Slate\Model;

/**
 * @property-read string $text A concatenated string of all of the descendant Text nodes of this node.
 */
abstract class TextContainingNode implements JsonConvertible
{
    use JsonStringConvertible;

    /** Concatenate all the descendant text nodes of this node. */
    abstract protected function computeTextProperty(): string;

    public function __get(string $name): string
    {
        if ($name === 'text') {
            return $this->computeTextProperty();
        }

        throw new \Exception("Unknown property via __get(): {$name}");
    }
}
