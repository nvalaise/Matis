<?php

namespace App\Services\RandomPseudo;

use Illuminate\Support\Facades\Facade;


class RandomPseudoFacade extends Facade {
    
    protected static function getFacadeAccessor()
    {
		return 'randompseudo'; 
	}
}