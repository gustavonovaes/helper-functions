<?php

function parseInt(string $value): int
{
  return intval($value);
}

function parseFloat(string $value): float
{
  return floatval(str_replace(',', '.', $value));
}

/**
 * @param int|float $number
 */
function prettyNumber($number, int $precision = 1): string
{
  $round = round($number);

  if ($round < 1000) {
    return '' . $round;
  }

  if ($round < 1_000_000) {
    return round($round / 1_000, $precision) . ' mil';
  }

  if ($round < 1_000_000_000) {
    return round($round / 1_000_000, $precision) . ' mi';
  }

  return round($round / 1_000_000_000, $precision) . ' bi';
}
