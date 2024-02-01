<?php

/**
 * Get numeric value of mixed variable or zero if not numeric
 * @param string $number
 * @return int
 */
function _gnv($number) {
	return ((is_numeric($number) ? $number : 0) + 0);
}
