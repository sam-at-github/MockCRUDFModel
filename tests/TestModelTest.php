<?php
use MockCRUDFModels\TestModel;
use MockCRUDFModels\TestModels;

class TestModelTest extends PHPUnit_Framework_TestCase
{
  private $m = null;
  private $objs = [
    ['name' => 'Mr. Nice'],
    ['name' => 'Narco'],
    ['name' => 'Bombasto'],
    ['name' => 'Celeritas'],
    ['name' => 'Magneta'],
    ['name' => 'RubberMan'],
    ['name' => 'Tornado']
  ];

  function setup() {
    $this->m = new TestModel();
    foreach($this->objs as $obj) {
      $this->m->create($obj);
    }
  }

  function teardown() {
  }

  function testSane() {
    $this->assertEquals(count($this->m->find()['result']), 7);
  }

  function testDelete() {
    $this->m->delete(1);
    $this->m->delete(2);
    $this->m->delete(3);
    $this->assertEquals(count($this->m->find()['result']), 4);
  }

  function testCreateUpdate() {
    $t = $this->m->create(['name' => 'Toenails']);
    $this->assertEquals(count($this->m->find()['result']), 8);
    $t['name'] = 'Magma';
    $t = $this->m->update($t);
    $this->assertEquals($t['name'], 'Magma');
  }

  /**
   * @expectedException Exception
   */
  function testGetBad() {
    $this->m->read(123);
  }

  /**
   * @expectedException Exception
   */
  function testDeleteBad() {
    $this->m->delete(123);
  }

  /**
   * @expectedException Exception
   */
  function testCreateBad() {
    $this->m->create($this->m->read(0));
  }

  /**
   * @expectedException Exception
   */
  function testUpdateBad() {
    $this->m->update(['noid' => 'ispresent']);
  }

  function testSerialization() {
    $s = serialize($this->m);
    $m = unserialize($s);
    $this->assertEquals(count($m->find()['result']), 7);
  }
}
