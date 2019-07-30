<?php
namespace MockCRUDFModels;
use MockCRUDFModels\TestModel;

/**
 * Provides a hash of test models idemotently creating new ones on request.
 * @todo TestModels itself is not a model but probably should be.
 */
class TestModels
{
  private $models = [];

  public function read($name) {
    $this->models[$name] = isset($this->models[$name]) ? $this->models[$name] : new TestModel();
    return $this->models[$name];
  }
}
