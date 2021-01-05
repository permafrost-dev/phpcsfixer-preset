<?php

namespace Permafrost\Tests\Unit\Support;

use Permafrost\PhpCsFixerRules\Support\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /** @test */
    public function it_creates_a_new_collection_when_calling_the_create_static_method(): void
    {
        $c = Collection::create([]);

        $this->assertInstanceOf(Collection::class, $c);
    }

    /** @test */
    public function it_filters_items(): void
    {
        $c = new Collection(['a', 'b', 'c']);

        $this->assertEquals(['a', 'b'], array_values($c->filter(function ($item) { return $item === 'a' || $item === 'b'; })->toArray()));
        $this->assertEquals(['b'], array_values($c->filter(function ($item) { return $item === 'b'; })->toArray()));
    }

    /** @test */
    public function it_returns_an_array_from_to_array(): void
    {
        $c = new Collection(['a', 'b', 'c']);

        $this->assertIsArray($c->toArray());
        $this->assertEquals(['a', 'b', 'c'], $c->toArray());
    }

    /** @test */
    public function it_rejects_items(): void
    {
        $c = new Collection(['a', 'b', 'c']);

        $this->assertEquals(['c'], array_values($c->reject(function ($item) { return $item === 'a' || $item === 'b'; })->toArray()));
        $this->assertEquals(['a'], array_values($c->reject(function ($item) { return $item !== 'a'; })->toArray()));
        $this->assertEquals([], $c->reject(function ($item) { return true; })->toArray());
        $this->assertEquals(['a', 'b', 'c'], $c->reject(function ($item) { return false; })->toArray());
    }

    /** @test */
    public function it_iterates_each_item(): void
    {
        $c = new Collection(['a', 'b', 'c']);
        $cache = (object)['items' => [], 'counter' => 0];

        $c->each(function ($item) use ($cache) {
            $cache->items[] = $item;
            ++$cache->counter;
        });

        $this->assertSame(['a', 'b', 'c'], $cache->items);
        $this->assertEquals(3, $cache->counter);
    }

    /** @test */
    public function it_maps_items_and_returns_a_new_collection(): void
    {
        $collection = new Collection(['a', 'b', 'c']);
        $newCollection = $collection->map(function ($item) {
            return strtoupper($item) . '!';
        });

        $this->assertEquals(['A!', 'B!', 'C!'], $newCollection->toArray());
        $this->assertNotEquals($collection, $newCollection);
    }

    /** @test */
    public function it_excludes_an_array_of_items_and_returns_a_new_collection(): void
    {
        $collection = new Collection(['a', 'b', 'c', '1']);
        $newCollection1 = $collection->exclude(['b']);
        $newCollection2 = $collection->exclude([1], false);

        $this->assertEquals(['a', 'c', '1'], $newCollection1->toArray());
        $this->assertEquals(['a', 'b', 'c'], $newCollection2->toArray());

        $this->assertNotEquals($collection, $newCollection1);
    }

    /** @test */
    public function it_implodes_items_into_a_string(): void
    {
        $collection = new Collection(['a', 'b', 'c']);

        $this->assertEquals('a,b,c', $collection->implode(','));
        $this->assertEquals('a|b|c', $collection->implode('|'));
        $this->assertEquals('abc', $collection->implode(''));
    }

    /** @test */
    public function it_checks_if_an_item_exists_as_a_value(): void
    {
        $collection = new Collection(['a', 'b', 'c']);

        $this->assertTrue($collection->contains('a'));
        $this->assertTrue($collection->contains('b'));
        $this->assertTrue($collection->contains('c'));
        $this->assertFalse($collection->contains('d'));
        $this->assertFalse($collection->contains('A'));
        $this->assertFalse($collection->contains('aa'));
        $this->assertFalse($collection->contains(''));
        $this->assertFalse($collection->contains(null));
    }
}
