<?php

// function dd(): void
// {
//   foreach (func_get_args() as $value) {
//     var_dump($value);
//   }
//   exit;
// }

function globRecursive(string $pattern, int $flags = 0): array
{
  $files = glob($pattern, $flags);
  if ($files === false) {
    return [];
  }

  $dirs = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);
  if ($dirs === false) {
    return [];
  }

  foreach ($dirs as $dir) {
    $files = array_merge($files, globRecursive($dir . '/' . basename($pattern), $flags));
  }
  return $files;
}


/**
 * @return mixed|false False if request fail
 */
function httpGet(string $url, int $timeout = 5)
{
  $context = stream_context_create([
    'http' => [
      'method' => "GET",
      'header' => "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36\r\n",
      'timeout' => $timeout,
    ]
  ]);

  try {
    return @file_get_contents($url, false, $context);
  } catch (\Exception $e) {
    return false;
  }
}

function validaCPF(string $cpf): bool
{
  $cpf = preg_replace('/[^0-9]/is', '', $cpf);
  if (\is_null($cpf) || strlen($cpf) != 11) {
    return false;
  }

  if (preg_match('/(\d)\1{10}/', $cpf)) {
    return false;
  }

  // Faz o calculo para validar o CPF
  for ($t = 9; $t < 11; $t++) {
    for ($d = 0, $c = 0; $c < $t; $c++) {
      $d += $cpf[$c] * (($t + 1) - $c);
    }
    $d = ((10 * $d) % 11) % 10;
    if ($cpf[$c] != $d) {
      return false;
    }
  }

  return true;
}
