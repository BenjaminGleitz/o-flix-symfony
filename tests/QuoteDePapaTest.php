<?php

namespace App\Tests;

use App\Service\QuoteDePapa;
use PHPUnit\Framework\TestCase;

class QuoteDePapaTest extends TestCase
{
    /**
     * Véerifie que la méthode QuoteDePapa fonctionne comme prévu
     *
     * @return void
     */
    public function testQuoteDePapa(): void
    {

        $quoteDePapa = new QuoteDePapa;
        $result = $quoteDePapa->randomQuote();

        $randomQuoteArray = [
            "Le gras c'est la vie", // index 0
            "OK", // index 1
            "C'est pas faux !", // index 2
            "Des customs curry",
            "RTFM",
            "auto waouwaouwaing",
            "search('homme poilu')",
            "Toutou beignet",
            "cateogry",
            "Quand tu ajoute tu attribut à la manau, c'est l'attribut de Dana",
            "j'oublie vite les choses",
            "un clavier AZERTY en vaux deux",
            "Flash sur Firefox, c’est de l’Adobe !",
            "la technique du saumon hydrater un objet",
            "peut-être= sûr",
            "on est les ylusses!"
        ];

        // Pour que le test fonctionne, le resultat de la méthode
        // doit se trouver dans le tableau randomQuoteArray
        $this->assertContains($result, $randomQuoteArray);

        $this->assertTrue(true);
    }
}
