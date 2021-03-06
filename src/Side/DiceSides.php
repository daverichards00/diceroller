<?php

namespace daverichards00\DiceRoller\Side;

use daverichards00\DiceRoller\Exception\DiceException;
use InvalidArgumentException;

class DiceSides implements \Countable, \Iterator
{
    /** @var DiceSide[] */
    private $sides = [];

    /** @var bool */
    private $isNumeric = true;

    /**
     * DiceSides constructor.
     * @param array $sides
     * @throws InvalidArgumentException
     */
    public function __construct(array $sides = [])
    {
        if (! empty($sides)) {
            $this->set($sides);
        }
    }

    /**
     * @param array $sides
     * @return DiceSides
     * @throws InvalidArgumentException
     */
    public function set(array $sides): self
    {
        $this->sides = [];
        $this->isNumeric = true;

        foreach ($sides as $side) {
            $this->add($side);
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @return DiceSides
     * @throws InvalidArgumentException
     */
    public function add($value): self
    {
        $side = new DiceSide($value);
        $this->sides[] = $side;

        if (! $side->isNumeric()) {
            $this->isNumeric = false;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->sides;
    }

    /**
     * @return array
     */
    public function getAllValues(): array
    {
        return array_map(function (DiceSide $diceSide) {
            return $diceSide->getValue();
        }, $this->sides);
    }

    /**
     * @param int $index
     * @return DiceSide
     * @throws DiceException
     */
    public function getByIndex(int $index): DiceSide
    {
        if (! array_key_exists($index, $this->sides)) {
            throw new DiceException(
                sprintf("DiceSides does not contain a DiceSide at index %d", $index)
            );
        }
        return $this->sides[$index];
    }

    /**
     * @param int $index
     * @return mixed
     * @throws DiceException
     */
    public function getValueByIndex(int $index)
    {
        if (! array_key_exists($index, $this->sides)) {
            throw new DiceException(
                sprintf("DiceSides does not contain a DiceSide at index %d", $index)
            );
        }
        return $this->sides[$index]->getValue();
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return $this->isNumeric;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->sides);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->sides)->getValue();
    }

    /**
     *
     */
    public function next()
    {
        next($this->sides);
    }

    /**
     * @return int|mixed|null|string
     */
    public function key()
    {
        return key($this->sides);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return null !== $this->key();
    }

    /**
     *
     */
    public function rewind()
    {
        reset($this->sides);
    }
}
