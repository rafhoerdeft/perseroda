<?php

use Illuminate\Support\Facades\Crypt;

if (!function_exists('encode')) {
	function encode($id)
	{
		$encode = Crypt::encryptString($id);
		return $encode;
	}
}

if (!function_exists('decode')) {
	function decode($id)
	{
		$decode = Crypt::decryptString($id);
		return $decode;
	}
}
