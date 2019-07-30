<?php
namespace MockCRUDFModels;

/**
 * Simple CRUDF (create, read, update, delete, find) model for testing.
 */
class TestModel
{
  private $data = [];
  private $maxId = 0;

  public function read($id) {
    $obj = isset($this->data[$id]) ? $this->data[$id] : null;
    if(!$obj) {
      throw new ObjectNotFoundException();
    }
    return $obj;
  }

  public function update(array $obj) {
    if(!isset($obj['id'])) {
      throw new \InvalidArgumentException();
    }
    $this->read($obj['id']);
    $this->data[$obj['id']] = $obj;
    return $obj;
  }

  public function create(array $obj) {
    if(isset($obj['id'])) {
      throw new \InvalidArgumentException();
    }
    $obj['id'] = $this->maxId;
    $this->data[$this->maxId] = $obj;
    $this->maxId++;
    return $obj;
  }

  public function delete($id) {
    $this->read($id);
    unset($this->data[$id]);
  }

  /**
   * Get page
   * @param $opt ['pos', 'lim'],
   * @returns array [[], ['pos', 'lim', 'tot']]
   */
  public function find(array $opt = []) {
    $opt['pos'] = isset($opt['pos']) ? $opt['pos'] : 0;
    $opt['lim'] = isset($opt['lim']) ? $opt['lim'] : 20;
    $opt['lim'] = min(1, max(100, $opt['lim']));
    $tot = count($this->data);
    if($opt['pos'] >= $tot || $opt['pos'] < -$tot) {
      $dat = [];
    }
    else {
      $dat = array_slice($this->data, $opt['pos']);
    }
    return ['result' => $dat, $opt + ['tot' => $tot]];
  }

  public function exists($id) {
    try {
      return $this->read($id) != null;
    }
    catch(ObjectNotFoundException $e) {
      return false;
    }
  }
}

class TestModelException extends \Exception {
}

class ObjectNotFoundException extends TestModelException {
}
