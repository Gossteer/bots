<?php

declare(strict_types=1);

namespace App\Tests\unit\Infrastructure\Utils;

use App\Domain\SharedCore\Port\Utils\IUuidService;
use App\Infrastructure\Utils\UuidService;
use PHPUnit\Framework\TestCase;

class UuidServiceTest extends TestCase
{
    private IUuidService $service;

    public function setUp(): void
    {
        $this->service = new UuidService();
    }

    public function testGetUuid(): void
    {
        $uuid1 = $this->service->getUuid();
        $uuid2 = $this->service->getUuid();
        $uuid3 = $this->service->getUuid();
        $uuid4 = $this->service->getUuid();

        $this->assertNotEquals($uuid1, $uuid2);
        $this->assertNotEquals($uuid2, $uuid3);
        $this->assertNotEquals($uuid1, $uuid3);

        $parts1 = explode("-", $uuid1);
        $parts2 = explode("-", $uuid2);
        $parts3 = explode("-", $uuid3);
        $parts4 = explode("-", $uuid4);

        $this->assertParts($parts1);
        $this->assertParts($parts2);
        $this->assertParts($parts3);
        $this->assertParts($parts4);

        $this->assertTrue($this->compareParts($parts1, $parts2));
        $this->assertTrue($this->compareParts($parts2, $parts3));
        $this->assertTrue($this->compareParts($parts1, $parts3));
        $this->assertTrue($this->compareParts($parts3, $parts4));
    }

    /**
     * @param string[] $parts
     */
    private function assertParts(array $parts): void
    {
        $this->assertEquals(5, count($parts));
        $this->assertEquals(8, strlen($parts[0]));
        $this->assertEquals(4, strlen($parts[1]));
        $this->assertEquals(4, strlen($parts[2]));
        $this->assertEquals(4, strlen($parts[3]));
        $this->assertEquals(12, strlen($parts[4]));
        $this->assertEquals("7", substr($parts[2], 0, 1));
    }

    /**
     * @param string[] $parts1
     * @param string[] $parts2
     */
    private function compareParts(array $parts1, array $parts2): bool
    {
        return ($parts1[0] . $parts1[1] !== $parts2[0] . $parts2[1])
            || ($parts1[0] . $parts1[1] === $parts2[0] . $parts2[1] && $parts1[2] !== $parts2[2]);
    }
}
