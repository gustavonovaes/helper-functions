<?php


function isBetween(
  \DateTimeInterface $dateCompare,
  \DateTimeInterface $dateStart,
  \DateTimeInterface $dateEnd
): bool {
  if ($dateCompare < $dateStart) {
    return false;
  }

  if ($dateCompare > $dateEnd) {
    return false;
  }

  return true;
}

function timeElapsed(\DateTimeInterface $date): string
{
  $now = time();
  $time = $date->getTimestamp();

  $seconds = $now - $time;
  if ($seconds <= 60) {
    return "há 1 min";
  }

  $minutes = floor($seconds / 60);
  if ($minutes < 60) {
    return 'há ' . ($minutes == 1 ? '1 min' : $minutes . ' min');
  }

  $hours = floor($seconds / 3600);
  if ($hours <= 24) {
    return 'há ' . ($hours == 1 ? '1 hora' : $hours . ' horas');
  }

  $days = floor($seconds / 86400);
  if ($days <= 7) {
    return 'há ' . ($days == 1 ? '1 dia' : $days . ' dias');
  }

  $weeks = floor($seconds / 604800);
  if ($weeks < 4) {
    return 'há ' . ($weeks == 1 ? '1 semana' : $weeks . ' semanas');
  }

  $months = floor($seconds / 2419200);
  if ($months <= 12) {
    return 'há ' . ($months == 1 ? '1 mês' : $months . ' meses');
  }

  $years = floor($seconds / 29030400);
  return 'há ' . ($years == 1 ? '1 ano' : $years . ' anos');
}

function dateDiffMinutes(\DateTimeInterface $dateA, \DateTimeInterface $dateB): int
{
  $dateDiff = $dateA->diff($dateB);

  $minutes = $dateDiff->i
    + ($dateDiff->h * 60)
    + ($dateDiff->days * 24 * 60);

  return $minutes;
}

/**
 * @throws InvalidArgumentException If can't take the diff between the provided dates
 */
function dateDiffDays(\DateTimeInterface $dateA, \DateTimeInterface $dateB, bool $onlyWorkingDays = false): int
{
  if (!$onlyWorkingDays) {
    $diff = $dateA->diff($dateB)->days;

    if ($diff === false) {
      throw new \InvalidArgumentException("Can't take the diff between the provided dates");
    }

    return $diff;
  }

  $dateStart = $dateA;
  // DatePeriod excludes the end date, so 1 day is added
  $dateEnd = (new \DateTime($dateB->format('Y-m-d H:i:s')))->modify('+1 day');
  $period = new \DatePeriod($dateStart, new \DateInterval('P1D'), $dateEnd);
  return array_reduce(
    iterator_to_array($period),
    fn ($count, $date) => $count + (in_array($date->format('w'), ['0', '6']) ? 0 : 1),
    0
  );
}
