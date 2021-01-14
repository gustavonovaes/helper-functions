<?php

use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{

  /**
   * @covers cleanAccents
   */
  public function test_cleanAccents()
  {
    $phrase = "A pressa é inimiga da perfeição";
    $result = "A pressa e inimiga da perfeicao";

    $this->assertEquals($result, cleanAccents($phrase));
  }
}
