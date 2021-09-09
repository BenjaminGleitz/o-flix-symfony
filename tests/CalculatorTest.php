<?php

namespace App\Tests;

use App\Service\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{

    /**
     * On va vérifier que la méthode addition du service Calculator fonctionne correctement.
     *
     * @return void
     */
    public function testAddition(): void
    {
        // Prenons le hiffre 1 et 2
        // La méthode est censée me retourner le chiffre 3

        $calculator = new Calculator;
        $result = $calculator->addition(1,2);
        $this->assertEquals(3, $result);
    }
}
