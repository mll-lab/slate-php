<?php declare(strict_types=1);

namespace Prezly\Slate\Tests;

class ValueTest extends TestCase
{
    /** @dataProvider fixtures */
    public function testSerializeToJson(string $filePath): void
    {
        $json = $this->loadFixture($filePath);
        $value = $this->loadContentFromFixture($filePath);

        $json = implode(
            "\n",
            array_map(
                fn (string $line): string => \Safe\preg_replace('/^(\s+)/', '$1$1', $line),
                explode("\n", $json)
            )
        );

        $this->assertEquals(trim($json), \Safe\json_encode($value, JSON_PRETTY_PRINT));
    }

    /** @return iterable<array{string}> */
    public function fixtures(): iterable
    {
        yield 'document_with_text.json' => [__DIR__ . '/fixtures/document_with_text.json'];
    }
}
