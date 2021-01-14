<?php

use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{

  /**
   * @covers isBetween
   */
  public function test_isBetween()
  {
    $datePass = new \DateTime('-10 days');
    $dateToday = new \DateTime('today');
    $dateFuture = new \DateTime('+10 days');

    $dateFarAgo1 = new \DateTime('-15 days');
    $dateFarAgo2 = new \DateTime('+15 days');

    $this->assertTrue(isBetween($dateToday, $datePass, $dateFuture));
    $this->assertTrue(isBetween($datePass, $datePass, $dateFuture));
    $this->assertTrue(isBetween($dateFuture, $datePass, $dateFuture));

    $this->assertNotTrue(isBetween($dateFarAgo1, $datePass, $dateFuture));
    $this->assertNotTrue(isBetween($dateFarAgo2, $datePass, $dateFuture));
  }


  /**
   * @covers timeElapsed
   */
  public function test_timeElapsed()
  {
    $dates = [
      'há 1 min' => new \DateTime('now'),
      'há 1 min' => new \DateTime('-1 minute'),
      'há 10 min' => new \DateTime('-10 minutes'),
      'há 1 hora' => new \DateTime('-1 hour'),
      'há 12 horas' => new \DateTime('-12 hours'),
      'há 1 mês' => new \DateTime('-1 month'),
      'há 6 meses' => new \DateTime('-6 months'),
      'há 1 ano' => new \DateTime('-1 year'),
      'há 5 anos' => new \DateTime('-5 years'),
    ];

    foreach ($dates as $expect => $date) {
      $this->assertEquals($expect, timeElapsed($date));
    }
  }


  /**
   * @covers dateDiffMinutes
   */
  public function test_dateDiffMinutes()
  {
    $dateA = new \DateTimeImmutable('today');
    $dateB = $dateA->add(new \DateInterval('PT1M'));
    $dateC = $dateA->sub(new \DateInterval('PT1M'));
    $dateD = $dateA->sub(new \DateInterval('PT1H'));
    $dateE = $dateA->add(new \DateInterval('PT12H'));

    $this->assertEquals(1, dateDiffMinutes($dateA, $dateB));
    $this->assertEquals(1, dateDiffMinutes($dateA, $dateC));
    $this->assertEquals(60, dateDiffMinutes($dateA, $dateD));
    $this->assertEquals(720, dateDiffMinutes($dateA, $dateE));
  }

  /**
   * @covers dateDiffDays
   */
  public function test_dateDiffDays()
  {
    $dateA = new \DateTimeImmutable('today');
    $dateB = $dateA->add(new \DateInterval('P1D'));
    $dateC = $dateA->sub(new \DateInterval('P7D'));

    $this->assertEquals(1, dateDiffDays($dateA, $dateB));
    $this->assertEquals(7, dateDiffDays($dateA, $dateC));

    $dateFoo = new \DateTime('2021-01-01');
    $dateBar = new \DateTime('2021-01-15');
    $onlyWorkingDays = true;
    $this->assertEquals(11, dateDiffDays($dateFoo, $dateBar, $onlyWorkingDays));
  }
}
