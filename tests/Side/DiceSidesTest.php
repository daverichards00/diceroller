<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\Exception\DiceException;
use daverichards00\DiceRoller\Side\DiceSide;
use daverichards00\DiceRoller\Side\DiceSides;
use PHPUnit\Framework\TestCase;

class DiceSidesTest extends TestCase
{
    /** @var DiceSides */
    protected $sut;

    public function setUp()
    {
        $this->sut = new DiceSides(['a', 'b', 'c']);
    }

    public function testIsCountable()
    {
        $this->assertInstanceOf(\Countable::class, $this->sut);
        $this->assertSame(3, count($this->sut));
    }

    public function testIsIterable()
    {
        $this->assertInstanceOf(\Iterator::class, $this->sut);

        $result = [];
        foreach ($this->sut as $value) {
            $result[] = $value;
        }
        $this->assertSame(['a', 'b', 'c'], $result);
    }

    public function testAllSidesCanBeRetrieved()
    {
        $result = $this->sut->getAll();

        $this->assertInstanceOf(DiceSide::class, $result[0]);
        $this->assertSame('a', $result[0]->getValue());
        $this->assertInstanceOf(DiceSide::class, $result[1]);
        $this->assertSame('b', $result[1]->getValue());
        $this->assertInstanceOf(DiceSide::class, $result[2]);
        $this->assertSame('c', $result[2]->getValue());
    }

    public function testAllValuesCanBeRetrieved()
    {
        $result = $this->sut->getAllValues();
        $this->assertSame(['a', 'b', 'c'], $result);
    }

    public function testSideCanBeRetrievedByIndex()
    {
        $result = $this->sut->getByIndex(0);
        $this->assertInstanceOf(DiceSide::class, $result);
        $this->assertSame('a', $result->getValue());

        $result = $this->sut->getByIndex(2);
        $this->assertInstanceOf(DiceSide::class, $result);
        $this->assertSame('c', $result->getValue());
    }

    public function testSideValueCanBeRetrievedByIndex()
    {
        $result = $this->sut->getValueByIndex(0);
        $this->assertSame('a', $result);

        $result = $this->sut->getValueByIndex(2);
        $this->assertSame('c', $result);
    }

    public function testExceptionThrownWhenTryingToGetWithAnInvalidIndex()
    {
        $this->expectException(DiceException::class);
        $this->sut->getValueByIndex(999);
    }

    public function testSidesCanBeAdded()
    {
        $this->sut->add('d');
        $this->assertSame(4, count($this->sut));
    }

    public function testAllSidesCanBeSet()
    {
        $expected = ['d', 'e', 'f'];
        $this->sut->set($expected);
        $this->assertSame($expected, $this->sut->getAllValues());

        $expected = [1, 2, 3];
        $this->sut->set($expected);
        $this->assertSame($expected, $this->sut->getAllValues());
    }
    public function testIsNumericReturnsCorrectValue()
    {
        $this->sut->set([1, 2, 3]);
        $this->assertTrue($this->sut->isNumeric());

        $this->sut->set([1.1, 2.2, 3.3]);
        $this->assertTrue($this->sut->isNumeric());

        $this->sut->set([1, 2.2, 3]);
        $this->assertTrue($this->sut->isNumeric());

        $this->sut->set(['a', 'b', 'c']);
        $this->assertFalse($this->sut->isNumeric());

        $this->sut->set([1, 2, 'c']);
        $this->assertFalse($this->sut->isNumeric());

        $this->sut->set([1, 2.2, 'c']);
        $this->assertFalse($this->sut->isNumeric());

        $this->sut->set([4, 5, 6]);
        $this->assertTrue($this->sut->isNumeric());
    }
}
