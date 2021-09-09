<?php

namespace App\Service;

class Calculator {


    /**
     * Methodes permettant de faire des additions
     *
     * @param integer $x
     * @param integer $y
     * @return int Résultat de l'addition
     */
    public function addition(int $x, int $y) 
    {
        return $x + $y;
    }
}