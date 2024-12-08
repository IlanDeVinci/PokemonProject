<?php

trait Soins {
    // Trait for healing Pokemon
    public function soigner(): void {
        $this->pointsDeVieRestants = $this->getPointsDeVieMax();

    }
}
