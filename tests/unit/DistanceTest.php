<?php
include("logic.php");

class DistanceTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function TestDistanceFunction()
    {
        $this->assertEquals(Distance(51.355468, 11.10079, 51.84389877319336, 10.753299713134766), '59.37478389631');
    }
}

