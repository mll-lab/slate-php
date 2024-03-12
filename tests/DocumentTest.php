<?php declare(strict_types=1);

namespace Prezly\Slate\Tests;

final class DocumentTest extends TestCase
{
    public function testGetConcatenatedPlainTextContentOfInnerNodes(): void
    {
        $document = $this->loadDocumentFromFixture(__DIR__ . '/fixtures/document_with_text.json');

        $this->assertEquals(
            "I'd like to introduce you to a very important person!",
            $document->text
        );
    }
}
