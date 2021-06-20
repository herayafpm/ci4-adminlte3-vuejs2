<?php

namespace App\Validation;

class MyRules
{
	public function update_pass(string $str, $length = 6): bool
	{
		if (empty($str)) {
			return true;
		}
		if (strlen($str) >= $length) {
			return true;
		}
		return false;
	}
}
