<?php 
  class bok_orm {
    public $columns = array();
    public $table;
    public $where;
    public $query_elements = [' SELECT ', ' FROM ', ' WHERE ', ' LIMIT '];
    
    public function select($columns) {
      $this -> columns = $columns;
      return $this;
    }

    public function from($table) {
      $this -> table = $table;
      return $this;
    }
    
    public function where($where) {
      $this -> where = $where;
      return $this;
    }

    public function result($selectedColumns = "*") {
      $query = $this -> query_elements[0];
      // if the columns array is empty, select all columns else given columns
      if (count($this -> columns) >= 1 && !empty($this -> columns[0])) {
        $query .= implode(', ', $this -> columns);
      } else {
        $query .= $selectedColumns;
      }

      $query .= $this -> query_elements[1];
      $query .= $this -> table;

      if (!empty($this -> where)) {
          $query .= $this -> query_elements[2];
          $query .= $this -> where;
      }

      return $query;
    }
  }

  $my_orm = new bok_orm();
  $dynamic_id = rand(000000, 999999);

  if(isset($_POST['input'])){
    $selectors = $_POST['input'];
    $where = $_POST['where'];
    echo $my_orm -> select([$selectors]) -> from('useraccounts') -> where($where) -> result();
  }

?>