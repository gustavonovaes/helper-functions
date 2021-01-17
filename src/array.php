<?php

/**
 * @param string|int $key
 * @param mixed $value
 */
function arrayFilterByKeyValue(array $array, $key, $value): array
{
  return array_filter($array, fn ($_) => $_[$key] === $value);
}

/**
 * @param int|string $column
 */
function arrayFilterColumn(array $array, $column): array
{
  return array_filter(array_column($array, $column));
}

function arrayFilterByKeys(array $array, array $allowedKeys): array
{
  return array_filter($array, function ($row) use ($allowedKeys) {
    return count(array_intersect(array_keys($row), $allowedKeys)) > 0;
  });
}

function arrayFilterMap(array $arrayBase, callable $fn): array
{
  return array_filter(array_map($fn, $arrayBase));
}

function arrayCombine(array $keys, array $values): array
{
  $array = array_combine($keys, $values);
  if ($array === false) {
    throw new \InvalidArgumentException("Can't \$keys with \$values");
  }

  return $array;
}

/**
 * @param mixed $fillValue
 * 
 * @throws \InvalidArgumentException If can't combine $keys with $values
 */
function arrayCombineFill(array $keys, $fillValue): array
{
  return arrayCombine($keys, array_fill(0, count($keys), $fillValue));
}

/**
 * @param int|string $column
 *
 * @return int|float
 */
function arraySumColumn(array $array, $column)
{
  return array_sum(array_column($array, $column));
}

/**
 * @param mixed $column
 */
function arrayUniqueColumn(array $array, $column): array
{
  return array_unique(array_column($array, $column));
}

function arrayUniqueFilter(array $array): array
{
  return array_unique(array_filter($array));
}

function arrayUniqueMerge(array $arrayA, array $arrayB): array
{
  return array_unique(array_merge($arrayA, $arrayB));
}

function arrayMap(array $array, callable $fn): array
{
  return array_map($fn, $array);
}

function arrayFlat(array $array): array
{
  return array_reduce($array, 'array_merge', []);
}

function arrayFlatMap(array $array, callable $fn): array
{
  return arrayFlat(array_map($fn, $array));
}

/**
 * @param int|string $column
 */
function arrayGroupByColumn(array $array, $column): array
{
  $keys = arrayUniqueColumn($array, $column);

  $fn = function ($result, $item) use ($column) {
    $result[$item[$column]][] = $item;
    return $result;
  };

  $initial = arrayCombineFill($keys, []);

  return array_reduce($array, $fn, $initial);
}

/**
 * @example arrayMultisort($array, 'key_a', SORT_ASC, 'key_b', SORTNATURAL)
 */
function arrayMultisort(array $array, ...$args): array
{
  $fields = array_filter($args, fn ($field) => is_string($field));
  foreach ($fields as $n => $field) {
    $args[$n] = array_column($array, $field);
  }
  $args[] = &$array;

  call_user_func_array('array_multisort', $args);
  return array_pop($args);
}
