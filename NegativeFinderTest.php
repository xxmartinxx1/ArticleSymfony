<?php

namespace App\Repository;

use PHPUnit\Framework\TestCase;

class NegativeFinderTest extends TestCase
{
    public function testFindNegativeNumbers()
    {
        $numbers = [1, -2, 3, -4, 5];
        $finder = new NegativeFinder();
        $result = $finder->findNegativeNumbers($numbers);

        $this->assertEquals([-2, -4], $result);
    }
}