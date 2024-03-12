<?php declare(strict_types=1);

namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;

final class UnserializerTest extends TestCase
{
    /**
     * The top level node of the Slate model is the Value. Any JSON that doesn't
     * have a top-level document node is invalid.
     *
     * @see https://docs.slatejs.org/slate-core/value
     * @see https://docs.slatejs.org/slate-core/document
     */
    public function testExpectTopLevelDocument(): void
    {
        $this->markTestSkipped('TODO add better validation errors');

        // @phpstan-ignore-next-line unreachable
        $this->expectException(\InvalidArgumentException::class);
        Value::fromJSON('{}');
    }

    /**
     * Since the Slate model always has a top-level node (the Value), the result of unserialization
     * should always be a single Value instance.
     */
    public function testReturnDocumentNode(): void
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/empty_document.json');
        $this->assertEmpty($document->nodes);
    }

    public function testSetDocumentData(): void
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_data.json');

        $this->assertEquals(
            (object) ['foo' => 'bar'],
            $document->data
        );
    }

    public function testOnlyAcceptBlockNodesForDocument(): void
    {
        $this->markTestSkipped('TODO add better validation errors');

        // @phpstan-ignore-next-line unreachable
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/document_with_inline_children.json');
        $this->expectException(\InvalidArgumentException::class);

        Value::fromJSON($fixture);
    }

    public function testAddChildrenToDocument(): void
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_flat_children.json');
        $nodes = $document->nodes;

        $this->assertCount(3, $nodes);
        foreach ($nodes as $node) {
            $this->assertInstanceOf(Block::class, $node);
        }

        $this->assertSame('paragraph', $nodes[0]->type);
        $this->assertSame('blockquote', $nodes[1]->type);
        $this->assertSame('list', $nodes[2]->type);
    }

    public function testNestChildren(): void
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_nested_children.json');
        $children = $document->nodes;

        // Second-level children
        $this->assertCount(2, $children[0]->nodes);
        $this->assertCount(0, $children[1]->nodes);
        $this->assertCount(2, $children[2]->nodes);
    }

    public function testLoadDocumentWithLeaves(): void
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_text.json');

        $this->assertCount(1, $document->nodes);
        $block = $document->nodes[0];

        assert($block instanceof Block);
        $this->assertSame('paragraph', $block->type);
        $this->assertCount(1, $block->nodes);

        $text = $block->nodes[0];
        assert($text instanceof Text);
        $this->assertCount(6, $text->leaves);

        $expected_texts = [
            'I\'d like to introduce ',
            'you',
            ' to a ',
            'very important ',
            'person',
            '!',
        ];

        foreach ($text->leaves as $i => $leaf) {
            $this->assertInstanceOf(Leaf::class, $leaf);
            $this->assertSame($expected_texts[$i], $leaf->text);
        }
    }

    public function testSetNodeData(): void
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/nodes_with_data.json');
        $block = $document->nodes[0];
        $this->assertEquals(
            (object) ['foo' => 'bar'],
            $block->data
        );

        $inline = $block->nodes[0];
        assert($inline instanceof Inline);
        $this->assertEquals(
            (object) ['name' => 'John Doe', 'id' => 1234],
            $inline->data
        );
    }

    public function testSetMarkData(): void
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/mark_with_data.json');

        $text = $document->nodes[0]->nodes[0];
        assert($text instanceof Text);

        $leaf = $text->leaves[0];
        $this->assertEquals(
            (object) ['href' => '/foo'],
            $leaf->marks[0]->data
        );
    }

    /** @dataProvider invalidDocuments */
    public function testFailDeserializingInvalidStructure(string $file, string $expectedError): void
    {
        $this->markTestSkipped('TODO add better validation errors');

        // @phpstan-ignore-next-line unreachable
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedError);
        $this->loadDocumentFromFixture($file);
    }

    /** @return iterable<array{string, string}> */
    public function invalidDocuments(): iterable
    {
        yield [__DIR__ . '/fixtures/invalid_document_01.json', 'Unexpected JSON value given: integer. An object is expected to construct Value.'];
        yield [__DIR__ . '/fixtures/invalid_document_02.json', 'Unexpected JSON value given: string. An object is expected to construct Value.'];
        yield [__DIR__ . '/fixtures/invalid_document_03.json', 'Invalid JSON structure given to construct Value. It should have "object" property.'];
        yield [__DIR__ . '/fixtures/invalid_document_04.json', 'Invalid JSON structure given to construct Value. It should have "object" property set to "value".'];
        yield [__DIR__ . '/fixtures/invalid_document_05.json', 'Unexpected JSON structure given for Value. A Value should have "document" property.'];
        yield [__DIR__ . '/fixtures/invalid_document_06.json', 'Unexpected JSON structure given for Value. The "document" property should be object.'];
        yield [__DIR__ . '/fixtures/invalid_document_07.json', 'Invalid JSON structure given to construct Document. It should have "object" property set to "document".'];
        yield [__DIR__ . '/fixtures/invalid_document_08_invalid_leaf_object.json', 'Invalid JSON structure given to construct Leaf. It should have "object" property set to "leaf".'];
    }
}
