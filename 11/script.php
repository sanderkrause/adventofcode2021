<?php

declare(strict_types=1);


final class Grid implements \Countable, \ArrayAccess
{
    private array $grid;

    private function __construct(int $size)
    {
        $this->grid = array_fill(0, $size, array_fill(0, $size, []));
    }

    public static function new(int $size): self
    {
        return new self($size);
    }

    public function add(Octopus $octopus): void
    {
        $this->grid[$octopus->y][$octopus->x] = $octopus;
    }

    public function get(int $y, int $x): ?Octopus
    {
        return $this->grid[$y][$x] ?? null;
    }

    public function count()
    {
        return count($this->grid);
    }

    public function offsetExists($offset)
    {
        return isset($this->grid[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->grid[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->grid[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->grid[$offset]);
    }

    public function countFlashes(): int
    {
        $numFlashes = 0;
        foreach ($this->grid as $yAxis) {
            /** @var Octopus $octopus */
            foreach ($yAxis as $octopus) {
                if ($octopus->hasFlashed()) {
                    $numFlashes++;
                    $octopus->resetFlash();
                }
            }
        }
        return $numFlashes;
    }

    public function countAll(): int
    {
        $count = 0;
        foreach ($this->grid as $yAxis) {
            $count += count($yAxis);
        }
        return $count;
    }
}

final class Octopus
{
    private const MAX_ENERGY_LEVEL = 9;
    private int $step;
    private bool $hasFlashed = false;
    public int $energy;
    public int $x;
    public int $y;
    private Grid $grid;

    public function __construct(int $energy, int $x, int $y, Grid $grid)
    {
        $this->energy = $energy;
        $this->x = $x;
        $this->y = $y;
        $this->grid = $grid;
    }

    public function increment(int $step): self
    {
        if (!isset($this->step) || $step > $this->step) {
            $this->step = $step;
        }
        $this->energy++;
        if ($this->energy > self::MAX_ENERGY_LEVEL && !$this->hasFlashed) {
            $this->flash();
        }
        return $this;
    }

    public function flash(): void
    {
//        echo "Octopus {$this->y},{$this->x} FLASHES on step {$this->step} \n";
        $this->hasFlashed = true;
        $this->incrementAround();
    }

    public function hasFlashed(): bool
    {
        return $this->hasFlashed;
    }

    public function resetFlash(): void
    {
        $this->energy = 0;
        $this->hasFlashed = false;
    }

    public function incrementAround(): void
    {
        $this->grid->get($this->y - 1, $this->x - 1)?->increment($this->step);
        $this->grid->get($this->y - 1, $this->x)?->increment($this->step);
        $this->grid->get($this->y - 1, $this->x + 1)?->increment($this->step);
        $this->grid->get($this->y, $this->x - 1)?->increment($this->step);
        $this->grid->get($this->y, $this->x + 1)?->increment($this->step);
        $this->grid->get($this->y + 1, $this->x - 1)?->increment($this->step);
        $this->grid->get($this->y + 1, $this->x)?->increment($this->step);
        $this->grid->get($this->y + 1, $this->x + 1)?->increment($this->step);
    }
}

$lines = file('input.txt');
//$lines = [
//    '5483143223',
//    '2745854711',
//    '5264556173',
//    '6141336146',
//    '6357385478',
//    '4167524645',
//    '2176841721',
//    '6882881134',
//    '4846848554',
//    '5283751526',
//];

$input = parseInput($lines);

/**
 * @param array $lines
 * @return Octopus[][]
 */
function parseInput(array $lines): Grid
{
    $grid = Grid::new(count($lines));
    foreach ($lines as $y => $line) {
        $xAxis = str_split(trim($line));
        foreach ($xAxis as $x => $energyLevel) {
            $grid->add(new Octopus((int)$energyLevel, $x, $y, $grid));
        }
    }

    return $grid;
}

function partOne(array $lines)
{
    $grid = parseInput($lines);

    $numFlashes = 0;
    for ($step = 0; $step < 100; $step++) {
        for ($y = 0; $y < count($grid); $y++) {
            for ($x = 0; $x < count($grid[$y]); $x++) {
                $grid[$y][$x]->increment($step);
            }
        }
        // Only count number of flashes after the step has completed
        $numFlashes += $grid->countFlashes();
    }

    return $numFlashes;
}

function partTwo(array $lines): ?int
{
    $grid = parseInput($lines);

    $step = 0;
    do {
        $step++;
        for ($y = 0; $y < count($grid); $y++) {
            for ($x = 0; $x < count($grid[$y]); $x++) {
                $grid[$y][$x]->increment($step);
            }
        }
    } while ($grid->countFlashes() !== $grid->countAll());

    return $step;
}

echo partOne($lines) . PHP_EOL;
echo partTwo($lines) . PHP_EOL;
