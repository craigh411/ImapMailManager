<?php

namespace Humps\MailManager\Tests;


use Humps\MailManager\Collections\Contracts\Collectable;

class AbstractCollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $collection;
    protected $collectable;

    public function setUp()
    {
        $this->collection = $this->getMockForAbstractClass('Humps\MailManager\Collections\AbstractCollection');
        $this->collectable = new MyCollectable();
    }

    /**
     * @test
     */
    public function it_adds_a_collectable_to_a_collection()
    {
        $this->collection->add($this->collectable);
        $this->assertEquals(1, count($this->collection));
    }

    /**
     * @test
     */
    public function it_adds_a_collectable_for_a_given_key()
    {
        $this->collection->add($this->collectable, 'foo');
        $this->assertEquals($this->collectable, $this->collection->get('foo'));
    }

    /**
     * @test
     */
    public function it_retrieves_an_item_from_the_collection()
    {
        $this->collection->add($this->collectable, 'bar');
        $this->assertEquals($this->collectable, $this->collection->get('bar'));
        $this->collection->add($this->collectable);
        $this->assertEquals($this->collectable, $this->collection->get(0));
    }


    /**
     * @test
     */
    public function it_returns_the_collection_as_json()
    {
        $this->collection->add($this->collectable, 'foo');
        $this->assertJson($this->collection->toJson());
    }


    /**
     * @test
     */
    public function it_removes_a_collectable_from_the_collection()
    {
        $this->collection->add($this->collectable);
        $this->collection->remove(0);
        $this->assertEquals(0, count($this->collection));
    }

    /**
     * @test
     */
    public function it_can_be_iterated_over()
    {
        $this->collection->add($this->collectable);
        $this->collection->add($this->collectable);

        foreach ($this->collection as $collectable) {
            $this->assertEquals($this->collectable, $collectable);
        }
    }

    /**
     * @test
     */
    public function it_serializes_the_collection_to_json()
    {
        $this->collection->add($this->collectable);
        $this->collection->add($this->collectable);
        $json = json_encode($this->collection);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['collection']));
    }

    /**
     * @test
     */
    public function it_serializes_the_collection_to_json_when_converted_to_string()
    {
        $this->collection->add($this->collectable);
        $this->collection->add($this->collectable);
        $json = $this->collection->__toString();
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['collection']));
    }

    /**
     * @test
     */
    public function it_clones_the_collection()
    {
        $this->collection->add($this->collectable);
        $this->collection->add($this->collectable);
        $clone = clone $this->collection;

        $collectable = new MyCollectable();
        $collectable->value = 'bar';
        $clone->add($collectable, 0);

        $this->assertEquals('foo', $this->collection->get(0)->value);
        $this->assertEquals('bar', $clone->get(0)->value);
    }
}

class MyCollectable implements Collectable{

    public $value = 'foo';
}
