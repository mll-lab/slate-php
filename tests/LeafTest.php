<?php declare(strict_types=1);

namespace Prezly\Slate\Tests;

use Prezly\Slate\Model\Leaf;

final class LeafTest extends TestCase
{
    public function testToJSON(): void
    {
        $leaf = new Leaf('foo 😎');
        $this->assertSame('{"object":"leaf","text":"foo 😎","marks":[]}', $leaf->toJSON(JSON_UNESCAPED_UNICODE));
        $this->assertSame('{"object":"leaf","text":"foo \ud83d\ude0e","marks":[]}', $leaf->toJSON());
    }
}
