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
    public function it_creates_a_collection_with_the_correct_items(): void
    {
        $c1 = Collection::create(['a']);

        $collections = [
            $c1,
            Collection::create($c1->toArray()),
            Collection::create(['a']),
        ];

        foreach ($collections as $collection) {
            $this->assertInstanceOf(Collection::class, $collection);
            $this->assertSame(['a'], $collection->toArray());
        }
    }

    /** @test */
    public function it_returns_the_correct_count(): void
    {
        $c = new Collection(['a', 'b', 'c']);

        $this->assertCount(3, $c->toArray());
        $this->assertSame(3, $c->count());
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
        $newCollection2 = $collection->exclude([1]);
        $newCollection3 = $collection->exclude(Collection::create([1])->toArray());

        $this->assertEquals(['a', 'c', '1'], $newCollection1->toArray());
        $this->assertEquals(['a', 'b', 'c'], $newCollection2->toArray());
        $this->assertEquals(['a', 'b', 'c'], $newCollection3->toArray());

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
    public function it_pushes_items_onto_the_array(): void
    {
        $collection = new Collection(['a', 'b']);

        $this->assertEquals(['a','b','c'], $collection->push('c')->toArray());
    }
}
