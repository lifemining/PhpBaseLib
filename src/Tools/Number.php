<?php

namespace Lifemining\PhpBaseLib\Tools;


class Number {
    public static function fillZero ($number, $fill = 2, $char = '0') {
	return str_pad((int) $number, $fill, $char, STR_PAD_LEFT);
    }
    // Une petite fonction utile pour borner les nombres entre 0 et 255.
    public static function bornes($nb,$min,$max) {
	if ($nb<$min) $nb=$min; // $nb est borné bas
	if ($nb>$max) $nb=$max; // $nb est Borné haut
	return $nb;
    }
    public static function isPair ($number) {
	return ($number % 2 === 0) ;
    }
    public static function formatFr ($number) {
	return number_format($number, 0, '.', ' ');
    }
}