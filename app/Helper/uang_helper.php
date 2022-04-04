<?php

function uang($angka, $type = false)
{
	if ($angka !== null && $angka !== '') {
		$uang = number_format($angka, 2, ',', '.');
	} else {
		$uang = null;
	}

	return ($type == false) ? $uang : 'Rp. ' . $uang;
}



function nominal($angka)
{
	if ($angka !== null && $angka !== '') {
		$nominal = number_format($angka, 0, '.', '.');
	} else {
		$nominal = null;
	}

	return $nominal;
}

function rm_nominal($nominal)
{
	if ($nominal !== null && $nominal !== '') {
		$number = str_replace('.', '', $nominal);
	} else {
		$number = null;
	}
	return $number;
}

function uang_koma($angka, $type = false)
{
	if ($angka !== null && $angka !== '') {
		$uang = number_format($angka, 0, ',', ',');
	} else {
		$uang = null;
	}

	return $uang;
}
