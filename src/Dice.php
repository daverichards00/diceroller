<?php

namespace daverichards00\DiceRoller;

use daverichards00\DiceRoller\Exception\DiceException;
use daverichards00\DiceRoller\Roller;
use daverichards00\DiceRoller\Roller\RollerInterface;
use daverichards00\DiceRoller\Side\DiceSide;
use daverichards00\DiceRoller\Side\DiceSides;
use daverichards00\DiceRoller\Side\DiceSidesFactory;
use InvalidArgumentException;

class Dice
{
    // Default Dice configurations
    const D4 = [1, 2, 3, 4];
    const D6 = [1, 2, 3, 4, 5, 6];
    const D8 = [1, 2, 3, 4, 5, 6, 7, 8];
    const D10 = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    const TENS_D10 = [0, 10, 20, 30, 40, 50, 60, 70, 80, 90];
    const D12 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    const D20 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
    const DF = [-1, 0, 1];

    /** @var RollerInterface */
    private $roller;

    /** @var DiceSides */
    private $sides;

    /** @var bool */
    private $isNumeric = true;

    /** @var null|int */
    private $value;

    /** @var bool */
    private $historyEnabled = false;

    /** @var array */
    private $history = [];

    /**
     * Dice constructor.
     * @param mixed $sides
     * @param RollerInterface $roller
     * @throws InvalidArgumentException
     */
    public function __construct($sides, RollerInterface $roller = null)
    {
        $this->setSides($sides);

        if (empty($roller)) {
            // Default: QuickRoller
            $roller = new Roller\QuickRoller();
        }
        $this->setRoller($roller);
    }

    /**
     * @param mixed $sides
     * @return Dice
     * @throws InvalidArgumentException
     */
    public function setSides($sides): self
    {
        if (! ($sides instanceof DiceSides)) {
            $sides = DiceSidesFactory::create($sides);
        }

        if (count($sides) < 2) {
            throw new InvalidArgumentException("A Dice must have at least 2 sides.");
        }

        $this->sides = $sides;

        if (! $sides->isNumeric()) {
            $this->isNumeric = false;
        }

        return $this;
    }

    /**
     * @return DiceSides
     */
    public function getSides(): DiceSides
    {
        return $this->sides;
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return $this->isNumeric;
    }

    /**
     * @param RollerInterface $roller
     * @return Dice
     */
    public function setRoller(RollerInterface $roller): self
    {
        $this->roller = $roller;
        return $this;
    }

    /**
     * @return RollerInterface
     */
    public function getRoller(): RollerInterface
    {
        return $this->roller;
    }

    /**
     * @param int $times
     * @return Dice
     * @throws InvalidArgumentException|DiceException
     */
    public function roll($times = 1): self
    {
        if ($times < 1) {
            throw new InvalidArgumentException("A Dice must be rolled at least 1 time.");
        }

        $numberOfSides = count($this->sides);

        while ($times--) {
            $this->setValue(
                $this->sides->getByIndex(
                    $this->getRoller()->roll(1, $numberOfSides) - 1
                )
            );
        }

        return $this;
    }

    /**
     * @param DiceSide $diceSide
     * @return Dice
     */
    private function setValue(DiceSide $diceSide): self
    {
        $this->value = $diceSide->getValue();

        if ($this->isHistoryEnabled()) {
            $this->addHistory($diceSide);
        }

        return $this;
    }

    /**
     * @return mixed
     * @throws DiceException
     */
    public function getValue()
    {
        if (null === $this->value) {
            throw new DiceException("Cannot get the value of a Dice that hasn't been rolled.");
        }

        return $this->value;
    }

    /**
     * @param bool $enabled
     * @return Dice
     */
    public function enableHistory(bool $enabled = true): self
    {
        $this->historyEnabled = $enabled;
        return $this;
    }

    /**
     * @param bool $disabled
     * @return Dice
     */
    public function disableHistory(bool $disabled = true): self
    {
        $this->historyEnabled = ! $disabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHistoryEnabled(): bool
    {
        return $this->historyEnabled;
    }

    /**
     * @param DiceSide $diceSide
     * @return Dice
     */
    private function addHistory(DiceSide $diceSide): self
    {
        $this->history[] = $diceSide;
        return $this;
    }

    /**
     * @return array
     */
    public function getHistory(): array
    {
        return array_map(function (DiceSide $diceSide) {
            return $diceSide->getValue();
        }, $this->history);
    }

    /**
     * @return Dice
     */
    public function clearHistory(): self
    {
        $this->history = [];
        return $this;
    }
}
