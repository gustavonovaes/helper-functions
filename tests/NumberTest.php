<?php

use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{

  /**
   * @covers prettyNumber
   */
  public function test_prettyNumber()
  {
    $this->assertSame('123', prettyNumber(123, 2));
    $this->assertSame('1.23 mil', prettyNumber(1_230, 2));
    $this->assertSame('1.23 mi', prettyNumber(1_230_000, 2));
    $this->assertSame('1.23 bi', prettyNumber(1_230_000_000, 2));
  }
}
