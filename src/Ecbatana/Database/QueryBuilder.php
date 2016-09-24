<?php
namespace Ecbatana\Database;

use PDO;

/**
 * Ecbatana QueryBuilder
 * 
 * @package Ecbatana\Database
 * @author armenthiz <armenthiz.me@gmail.com>
 * @copyright armenthiz
 * @license MIT
 */

class QueryBuilder
{
    /**
     * Query variable
     * This variable is used to hold the called query.
     * 
     * @var array
     */
    private $query = array();

    /**
     * Trace variable
     * This variable is used to hold the path of called query.
     * 
     * @var array
     */
    private $trace = array();

    /**
     * Result function
     * This method is used as endpoint for action select
     * 
     * @param  $table string
     * @return array on success
     */
    public function result($table)
    {
        switch ($this->trace[0]) {
            case 'select':
                $column = '';
                $table = $table;
                $cond = '';
                $dbc = $this->getDB();

                foreach ($this->query['select'] as $value) {
                    if (count($this->query['select']) == 1) {
                        $column .= $value;
                    } else {
                        $column .= $value . ',';
                    }
                }
                if (strpos($column, ',') > 0) {
                    $column = substr_replace($column, '', -1);
                }

                $query = 'SELECT ' . $column . ' FROM ' . $table;

                    // CONDITIONS

                $explCond = explode(',', $this->trace['cond']);

                foreach ($explCond as $value) {
                    switch ($value) {
                        case 'where':
                            $query .= ' WHERE';

                            foreach ($this->query['where'] as $column) {
                                $query .= ' ' . $column;
                            }
                        break;

                        case 'andWhere':
                            $query .= ' AND';

                            foreach ($this->query['andWhere'] as $column) {
                                $query .= ' ' . $column;
                            }
                        break;

                        case 'orWhere':
                            $query .= ' OR';

                            foreach ($this->query['orWhere'] as $column) {
                                $query .= ' ' . $column;
                            }
                        break;

                        case 'group':
                            $query .= ' GROUP BY';

                            foreach ($this->query['group'] as $column) {
                                $query .= ' ' . $column;
                            }
                        break;

                        case 'order':
                            $query .= ' ORDER BY';

                            foreach ($this->query['order'] as $column) {
                                $query .= ' ' . $column;
                            }
                        break;

                        case 'andOrder':
                            $query .= ' ,';

                            foreach ($this->query['andOrder'] as $column) {
                                $query .= ' ' . $column;
                            }
                        break;

                        case 'limit':
                            $query .= ' LIMIT';

                            foreach ($this->query['limit'] as $column) {
                                if (count($this->query['limit']) == 1)
                                {
                                    $query .= ' ' . $column . ' ';
                                } else {
                                    $query .= ' ' . $column . ',';
                                }
                            }
                            $query = substr_replace($query, '', -1);
                        break;

                        default:

                        break;
                    }
                }

                $result = $dbc->query($query)->fetchAll(PDO::FETCH_ASSOC);
                $this->query = '';
                $this->trace = '';
                return $result;

            break;

            default:
                // TODO
            break;
        }
    }

    /**
     * Limit function
     * This method is used to set the limit function for SQL Statement
     * 
     * @param  $start int
     * @param  $length string
     * @return TODO
     */
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

    /**
     * Order function
     * This method is used to set the order SQL Statement
     * 
     * @param  $column string
     * @param  $action string
     * @return TODO
     */
    public function order($column, $action)
    {
        $this->trace['cond'] .= 'order,';

        $this->query['order'] = array($column, $action);
        return $this;
    }

    /**
     * Group function
     * This method is used to set the group SQL Statement
     * 
     * @param  $column string
     * @param  $action string
     * @return TODO
     */
    public function group($column, $action = null)
    {
        if ($action != null || !empty($action))
        {
            $this->trace['cond'] .= 'group,';

            $this->query['group'] = array($column, $action);
            return $this;
        }
    }

    /**
     * AndOrder function
     * This method is used to set multiple order SQL Statement, this
     * function is called after the Group function
     * 
     * @param  $column string
     * @param  $action string
     * @return TODO
     */
    public function andOrder($column, $action)
    {
        $this->trace['cond'] .= 'andOrder,';

        $this->query['andOrder'] = array($column, $action);
        return $this;
    }

    /**
     * Where function
     * This method is used to set the where SQL Statement
     * 
     * @param  $column string
     * @param  $conditions string
     * @param  $value string
     * @return TODO
     */
    public function where($column, $conditions = null, $value)
    {
        if ($conditions != null || !empty($conditions))
        {
            $this->trace['cond'] .= 'where,';

            $this->query['where'] = array($column, $conditions, '\'' . $value . '\'');
            return $this;
        }
    }

    /**
     * AndWhere Function
     * This method is used to set AND for Where function, This function is
     * called after the Where function or OrWhere function
     * 
     * @param  $column string
     * @param  $conditions string
     * @param  $value string
     * @return TODO
     */
    public function andWhere($column, $conditions = null, $value)
    {
        if ($conditions != null || !empty($conditions))
        {
            $this->trace['cond'] .= 'andWhere,';

            $this->query['andWhere'] = array($column, $conditions, '\'' . $value . '\'');
            return $this;
        }
    }

    /**
     * OrWhere function
     * This method is used to set OR for Where function, this function is
     * called after the Where function or AndWhere function
     * 
     * @param  $column string
     * @param  $conditions string
     * @param  $value string
     * @return TODO
     */
    public function orWhere($column, $conditions = null, $value)
    {
        if ($conditions != null || !empty($conditions))
        {
            $this->trace['cond'] .= 'orWhere,';

            $this->query['orWhere'] = array($column, $conditions, '\'' . $value . '\'');
            return $this;
        }
    }

    /**
     * Select function
     * This method is used to set Select SQL Statement
     * 
     * @return TODO
     */
    public function select()
    {
        $this->trace[] = 'select';
        $this->trace['cond'] = '';

        $args = func_get_args();
        $this->query['select'] = $args;
        return $this;
    }

    /**
     * Table function
     * This method is used to set the name of table for use in SQL Statement,
     * this method is usually called for inserting, updating, or deleting 
     * statement
     * 
     * @param  $table string
     * @return TODO
     */
    public function table($table)
    {
        $this->trace[] = 'table';
        $this->trace['cond'] = '';

        $this->query['table'] = $table;
        return $this;
    }

    /**
     * Insert function
     * This method is used to set the insert SQL Statement
     * 
     * @param  $insertData string
     * @return TODO
     */
    public function insert($insertData)
    {
        $trace = $this->trace[0];
        $dbc = $this->getDB();
        $table = $this->query['table'];
        $query = 'INSERT INTO ' . $table;
        $column = '';
        $data = '';
        $param = '';
        $execute = array();
        $count = count($insertData);

        switch ($trace) {
            case 'table':
                foreach ($insertData as $key => $value) {
                    if ($count > 1)
                    {
                        $column .= $key . ',';
                        $data .= $value . ',';
                        $param .= ':p' . $key . ',';
                        $execute[':p' . $key] = $value;
                    } else {
                        $column .= $key;
                        $data .= $value;
                        $param .= $key;
                        $execute[':p' . $key] = $value;
                    }
                }

                // remove trailing commas
                if ($count > 1)
                {
                    $column = ltrim(substr_replace($column, '', -1));
                    $data = ltrim(substr_replace($data, '', -1));
                    $param = ltrim(substr_replace($param, '', -1));
                }

                $query .= ' (' . $column . ') VALUES' . '(' . $param . ')';
                $statement = $dbc->prepare($query);
                $statement->execute($execute);
                $this->query = '';
                $this->trace = '';
            break;

            default:
                # code...
            break;
        }
    }

    /**
     * Update function
     * This method is used to set the update SQL Statement
     * 
     * @param  $updateData string
     * @return TODO
     */
    public function update($updateData)
    {
    	$trace = $this->trace;
        $dbc = $this->getDB();
        $table = $this->query['table'];
        $query = 'UPDATE ' . $table;
        $column = '';
        $data = '';
        $param = '';
        $where = '';
        $set = '';
        $execute = array();
        $count = count($updateData);

    	foreach ($trace as $value) {
	        switch ($value) {
	            case 'table':
	                foreach ($updateData as $key => $value) {
	                    if ($count > 1)
	                    {
	                        $data .= $value . ',';
	                        $param .= $key . '=:p' . $key . ',';
	                        $execute[':p' . $key] = $value;
	                    } else {
	                        $data .= $value;
	                        $param .= $key . '=:p' . $key;
	                        $execute[':p' . $key] = $value;
	                    }
	                }

	                // remove trailing commas
	                if ($count > 1)
	                {
	                    $column = ltrim(substr_replace($column, '', -1));
	                    $data = ltrim(substr_replace($data, '', -1));
	                    $param = ltrim(substr_replace($param, '', -1));
	                }

	                $set .= ' SET '.  $param;
	                // $statement = $dbc->prepare($query);
	                // $statement->execute($execute);
	                // $this->query = '';
	                // $this->trace = '';
	            break;

	            case 'where,':
                    $where .= ' WHERE';

                    foreach ($this->query['where'] as $column) {
                        $where .= ' ' . $column;
                    }
	            break;

	            default:
	                # code...
	            break;
	        }
    	}
    	
    	$query .= $set . $where;
        $statement = $dbc->prepare($query);
        $statement->execute($execute);
        $this->query = '';
        $this->trace = '';
    }

    /**
     * Delete function
     * This method is used to set delete SQL Statement
     * 
     * @return TODO
     */
    public function delete()
    {
        $trace = $this->trace;
        $dbc = $this->getDB();
        $query = 'DELETE FROM';
        $column = '';
        $data = '';
        $param = '';
        $execute = array();

        switch ($trace[0]) {
            case 'table':
                    $table = $this->query['table'];
                    $query .= ' ' . $table;
                    switch ($trace['cond']) {
                        case 'where,':
                            $query .= ' WHERE';

                            foreach ($this->query['where'] as $column) {
                                $query .= ' ' . $column;
                            }
                            $result = $dbc->query($query)->execute();
                            return $result;
                            $this->query = '';
                            $this->trace = '';
                            break;
                        
                        default:
                            $result = $dbc->query($query)->execute();
                            return $result;
                            $this->query = '';
                            $this->trace = '';
                            break;
                    }
                break;
            
            default:
                # code...
                break;
        }
    }
}