<?php

namespace App\Services\RandomPseudo;

use Illuminate\Support\Facades\Http;

class RandomPseudo {

	static private $baseUrl = "http://names.drycodes.com/1";

	protected static function format($pseudo) {
		return str_replace("_","",$pseudo[0]);
	}

	protected static function request($option) {
		if ($pseudo = Http::get(self::$baseUrl . $option)) {
			return self::format($pseudo);
		}

		return 'Tintin';
	}

	public static function generate() {
		return self::funny();
	}

	public static function starWars() 
	{ 
		$option = "?nameOptions=starwarsCharacters";

		return self::request($option);
	}

	public static function funny() 
	{ 
		$option = "?nameOptions=funnyWords";

		return self::request($option);
	}

	public static function president() 
	{ 
		$option = "?nameOptions=presidents";

		return self::request($option);
	}

	public static function boy() 
	{ 
		$option = "?nameOptions=boy_names";

		return self::request($option);
	}

	public static function girl() 
	{ 
		$option = "?nameOptions=girl_names";

		return self::request($option);
	}

	public static function planets() 
	{ 
		$option = "?nameOptions=planets";

		return self::request($option);
	}

	public static function films() 
	{ 
		$option = "?nameOptions=films";

		return self::request($option);
	}
}