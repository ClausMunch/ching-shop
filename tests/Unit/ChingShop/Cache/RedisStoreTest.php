<?php

namespace Testing\Unit\ChingShop\Cache;

use ChingShop\Cache\RedisStore;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Predis\ClientInterface;
use Testing\Unit\UnitTest;
use Vetruvet\PhpRedis\Database;

/**
 * Class RedisStoreTest
 *
 * @package Testing\Unit\ChingShop\Cache
 */
class RedisStoreTest extends UnitTest
{
    /** @var RedisStore */
    private $redisStore;

    /** @var Database|MockObject */
    private $database;

    /** @var ClientInterface|MockObject */
    private $client;

    /**
     * Set up Redis store with mock phpredis database.
     */
    public function setUp()
    {
        parent::setUp();

        $this->database = $this->makeMock(Database::class);
        $this->client = $this->makeMock(ClientInterface::class);

        $this->redisStore = new RedisStore($this->database);
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(RedisStore::class, $this->redisStore);
    }

    /**
     * Should convert strictly false values to null.
     */
    public function testConvertsFalseToNull()
    {
        $this->databaseWillReturnClient();

        $this->client->expects($this->once())
            ->method('__call')
            ->with('get')
            ->willReturn(false);

        $value = $this->redisStore->get('foobar');

        $this->assertNull($value);
    }

    /**
     * Should not change any non-false values.
     */
    public function testDoesNotChangeNonFalseValues()
    {
        $this->databaseWillReturnClient();

        $redisValue = 'hello';
        $this->client->expects($this->once())
            ->method('__call')
            ->with('get')
            ->willReturn(serialize($redisValue));

        $value = $this->redisStore->get('foobar');

        $this->assertSame($redisValue, $value);
    }

    /**
     * Mock Redis database will return mock client.
     */
    private function databaseWillReturnClient()
    {
        $this->database->expects($this->once())
            ->method('connection')
            ->willReturn($this->client);
    }
}
