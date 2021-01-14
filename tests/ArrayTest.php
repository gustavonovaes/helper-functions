<?php

use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
  private array $array1 = [
    ['A' => 1, 'B' => 2, 'C' => 3],
    ['A' => 10, 'B' => 42, 'C' => 30],
    ['A' => 100, 'B' => 200, 'C' => 300, 'X' => -1],
  ];

  private array $array2 = [
    ['id' => 1, 'value' => 100],
    ['id' => 2, 'value' => 101],
    ['id' => 1, 'value' => 110],
    ['id' => 1, 'value' => 200],
    ['id' => 3, 'value' => 201],
  ];

  /**
   * @covers arrayFilterByKeyValue
   */
  public function test_arrayFilterByKeyValue()
  {
    $array = arrayFilterByKeyValue($this->array1, 'B', 42);

    $this->assertEquals(['A' => 10, 'B' => 42, 'C' => 30], $array[1]);
  }


  /**
   * @covers arrayFilterColumn
   */
  public function test_arrayFilterColumn()
  {
    $array = arrayFilterColumn($this->array1, 'B');

    $this->assertEquals([2, 42, 200], $array);
  }


  /**
   * @covers arrayFilterByKeys
   */
  public function test_arrayFilterByKeys()
  {
    $array = arrayFilterByKeys($this->array1, ['X']);

    $this->assertCount(1, $array);
    $this->assertEquals(['A' => 100, 'B' => 200, 'C' => 300, 'X' => -1], current($array));
  }


  /**
   * @covers arrayFilterMap
   */
  public function test_arrayFilterMap()
  {
    $array = arrayFilterMap($this->array1, function ($arr) {
      if ($arr['B'] === 42) {
        return false;
      }

      $arr['FOO'] = 'bar';

      return $arr;
    });

    $this->assertCount(2, $array);
  }


  /**
   * @covers arrayCombine
   */
  public function test_arrayCombine()
  {
    $array = arrayCombine(['foo'], ['bar']);

    $this->assertEquals(['foo' => 'bar'], $array);

    if (error_reporting() & E_WARNING) {
      $this->expectWarning();
    } else {
      $this->expectException('InvalidArgumentException');
    }

    arrayCombine(['foo'], ['bar', 'baz']);
  }


  /**
   * @covers arraySumColumn
   */
  public function test_arraySumColumn()
  {
    $result = arraySumColumn($this->array1, 'B');

    $this->assertEquals(244, $result);
  }


  /**
   * @covers arrayUniqueColumn
   */
  public function test_arrayUniqueColumn()
  {
    $array = arrayUniqueColumn($this->array2, 'id');

    $this->assertCount(3, $array);
    $this->assertEquals([1, 2, 3], array_values($array));
  }


  /**
   * @covers arrayUniqueFilter
   */
  public function test_arrayUniqueFilter()
  {
    $array = arrayUniqueFilter([1, false, '', 2, '', false]);

    $this->assertCount(2, $array);
    $this->assertEquals([1, 2], array_values($array));
  }


  /**
   * @covers arrayUniqueMerge
   */
  public function test_arrayUniqueMerge()
  {
    $array = arrayUniqueMerge([1, false, 2, false], [2, 3]);

    $this->assertCount(4, $array);
    $this->assertEquals([1, false, 2, 3], array_values($array));
  }


  /**
   * @covers arrayMap
   */
  public function test_arrayMap()
  {
    $array = arrayMap([1, false, 2, false], fn () => 1);

    $this->assertEquals([1, 1, 1, 1], array_values($array));
  }


  /**
   * @covers arrayFlat
   */
  public function test_arrayFlat()
  {
    $array = arrayFlat([[1], [2]]);

    $this->assertEquals([1, 2], array_values($array));
  }


  /**
   * @covers arrayFlatMap
   */
  public function test_arrayFlatMap()
  {
    $array = arrayFlatMap([1, 2], fn ($val) => [$val]);

    $this->assertEquals([1, 2], array_values($array));
  }


  /**
   * @covers arrayCombineFill
   */
  public function test_arrayCombineFill()
  {
    $array = arrayCombineFill([1, 2], [
      'id' => null,
      'value' => null
    ]);

    $expect = [
      1 => [
        'id' => null,
        'value' => null
      ],
      2 => [
        'id' => null,
        'value' => null
      ]
    ];

    $this->assertEquals($expect, $array);
  }


  /**
   * @covers arrayGroupByColumn
   */
  public function test_arrayGroupByColumn()
  {
    $array = arrayGroupByColumn($this->array2, 'id');

    $id = 1;

    $this->assertCount(3, $array[$id]);
  }


  /**
   * @covers arrayMultisort
   */
  public function test_arrayMultisort()
  {
    $tmp = [
      ['id' => 1, 'value' => 'apple'],
      ['id' => 2, 'value' => 'img50.jpg'],
      ['id' => 2, 'value' => 'img9.jpg'],
      ['id' => 2, 'value' => 'img10.jpg'],
      ['id' => -1, 'value' => 'banana'],
    ];

    $array = arrayMultisort($tmp, 'id', SORT_ASC, 'value', SORT_DESC);
    $this->assertEquals('banana', $array[0]['value']);

    $array = arrayMultisort($tmp, 'id', SORT_DESC, 'value', SORT_DESC);
    $this->assertEquals('img9.jpg', $array[0]['value']);

    $array = arrayMultisort($tmp, 'id', SORT_DESC, 'value', SORT_ASC);
    $this->assertEquals('img10.jpg', $array[0]['value']);

    $array = arrayMultisort($tmp, 'id', SORT_DESC, 'value', SORT_DESC);
    $this->assertEquals('img9.jpg', $array[0]['value']);

    $array = arrayMultisort($tmp, 'id', SORT_DESC, 'value', SORT_NATURAL);
    $this->assertEquals('img9.jpg', $array[0]['value']);
  }
}
