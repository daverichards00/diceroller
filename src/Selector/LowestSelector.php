<?php

namespace daverichards00\DiceRoller\Selector;

use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\Collection\DiceCollection;
use InvalidArgumentException;

class LowestSelector implements DiceSelectorInterface
{
    /** @var int */
    private $quantity;

    /**
     * LowestSelector constructor.
     * @param int $quantity
     */
    public function __construct(int $quantity = 1)
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException("Quantity must be at least 1.");
        }

        $this->quantity = $quantity;
    }

    /**
     * @param DiceCollection $diceCollection
     * @return DiceCollection
     */
    public function select(DiceCollection $diceCollection): DiceCollection
    {
        $dice = $diceCollection->getDice();

        usort($dice, function (Dice $a, Dice $b) {
            return $a->getValue() <=> $b->getValue();
        });

        return new DiceCollection(
            array_slice($dice, 0, $this->quantity)
        );
    }
}
