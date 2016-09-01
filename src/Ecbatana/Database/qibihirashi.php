<?php
namespace Ecbatana\Database;

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
				$select = $this->query['select'];
				foreach ($select as $key => $value) {
					if (! is_array($value)){
						$this->select .= $value . ', ';
					} else {
						$select['where'] = $select['where'][0] . ' ' . $select['where'][1] . ' ' . $select['where'][2];

					}
				}

				$build = array(
					'select' => substr_replace($this->select, '', -2),
					'where' => $select['where']
				);

				$query = "SELECT {$build['select']} FROM {$table} WHERE {$build['where']}";
				echo $query;
				$db = $this->getDB();
				$result = $db->query($query)->fetchAll(\PDO::FETCH_ASSOC);
				return $result;

				break;
			
			default:
				echo 'ayy';
				break;
		}
	}

	public function where($rowname, $conditions = null, $value)
	{
		$this->trace[] = 'where';

		$this->query['select']['where'] = array($rowname, '=', $value);
		return $this;
	}

	public function select()
	{
		$this->trace[] = 'select';

		$args = func_get_args();
		$this->query['select'] = $args;
		return $this;
	}
}