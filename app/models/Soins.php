<?php

trait Soins {
    public function soigner(): void {
        $this->pointsDeVieRestants = $this->getPointsDeVieMax();

    }
}
