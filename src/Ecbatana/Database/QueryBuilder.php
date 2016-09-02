<?php
namespace Ecbatana\Database;

use PDO;

class QueryBuilder
{
	private $selectAll;
	private $query = array();
	private $trace = array();
	private $select;
	private $where;

	public function all()
	{
		$this->selectAll = 'SELECT * FROM users';
		return $this;
	}

	public function result($table)
	{
		switch ($this->trace[0]) {
			case 'select':
				$row = '';
				$table = $table;
				$cond = '';
				
				foreach ($this->query['select'] as $value) {
					if (count($this->query['select']) == 1) {
						$row .= $value; 
					} else {
						$row .= $value . ',';
					}
				}
				if (strpos($row, ',') > 0) {
					$row = substr_replace($row, '', -1);
				}

				$query = 'SELECT ' . $row . ' FROM ' . $table;

				// CONDITIONS

				$explCond = explode(',', $this->trace['cond']);
				
				foreach ($explCond as $value) {
					switch ($value) {
						case 'where':
							$query .= ' WHERE';

							foreach ($this->query['where'] as $row) {
								$query .= ' ' . $row;
							}
							break;

						case 'andWhere':
							$query .= ' AND';

							foreach ($this->query['andWhere'] as $row) {
								$query .= ' ' . $row;
							}
							break;

						case 'orWhere':
							$query .= ' OR';

							foreach ($this->query['orWhere'] as $row) {
								$query .= ' ' . $row;
							}
							break;

						case 'order':
							$query .= ' ORDER BY';

							foreach ($this->query['order'] as $row) {
								$query .= ' ' . $row;
							}

							break;

						case 'limit':
							$query .= ' LIMIT';

							foreach ($this->query['limit'] as $row) {
								if (count($this->query['limit']) == 1)
								{
									$query .= ' ' . $row . ' ';
								} else {
									$query .= ' ' . $row . ','; 
								}
							}
							$query = substr_replace($query, '', -1);
							break;
						
						default:
							# code...
							break;
					}
				}				

				// echo $query;
				// print_r($this->query);
				// print_r($this->trace);

				$dbc = $this->getDB();
				$result = $dbc->query($query)->fetchAll(PDO::FETCH_ASSOC);
				$this->query = '';
				$this->trace = '';
				return $result;

				break;
			
			default:
				echo 'ayy';
				break;
		}
	}

	public function limit($start, $length = '')
	{
		$this->trace['cond'] .= 'limit,';

		if ($length != '') {
			$this->query['limit'] = array($start, $length);
		} else {
			$this->query['limit'] = array($start);
		}
		return $this;
	}

	public function order($row, $action)
	{
		$this->trace['cond'] .= 'order,';

		$this->query['order'] = array($row, $action);
		return $this;
	}

	public function where($rowname, $conditions = null, $value)
	{
		$this->trace['cond'] .= 'where,';

		$this->query['where'] = array($rowname, '=', '\'' . $value . '\'');
		return $this;
	}

	public function andWhere($rowname, $conditions = null, $value)
	{
		$this->trace['cond'] .= 'andWhere,';

		$this->query['andWhere'] = array($rowname, '=', '\'' . $value . '\'');
		return $this;
	}

	public function orWhere($rowname, $conditions = null, $value)
	{
		$this->trace['cond'] .= 'orWhere,';

		$this->query['orWhere'] = array($rowname, '=', '\'' . $value . '\'');
		return $this;
	}

	public function select()
	{
		$this->trace[] = 'select';
		$this->trace['cond'] = '';

		$args = func_get_args();
		$this->query['select'] = $args;
		return $this;
	}
}