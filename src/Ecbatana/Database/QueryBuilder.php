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
                            # code...
                            break;
                    }
                }                

                // echo $query;
                // print_r($this->query);
                // print_r($this->trace);

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

    public function order($column, $action)
    {
        $this->trace['cond'] .= 'order,';

        $this->query['order'] = array($column, $action);
        return $this;
    }

    public function group($column, $action = null)
    {
        if ($action != null || !empty($action))
        {
            $this->trace['cond'] .= 'group,';

            $this->query['group'] = array($column, $action);
            return $this;
        }
    }

    public function andOrder($column, $action)
    {
        $this->trace['cond'] .= 'andOrder,';

        $this->query['andOrder'] = array($column, $action);
        return $this;
    }

    public function where($column, $conditions = null, $value)
    {
        if ($conditions != null || !empty($conditions))
        {
            $this->trace['cond'] .= 'where,';

            $this->query['where'] = array($column, $conditions, '\'' . $value . '\'');
            return $this;
        }
    }

    public function andWhere($column, $conditions = null, $value)
    {
        if ($conditions != null || !empty($conditions))
        {
            $this->trace['cond'] .= 'andWhere,';

            $this->query['andWhere'] = array($column, $conditions, '\'' . $value . '\'');
            return $this;
        }
    }

    public function orWhere($column, $conditions = null, $value)
    {
        if ($conditions != null || !empty($conditions))
        {
            $this->trace['cond'] .= 'orWhere,';

            $this->query['orWhere'] = array($column, $conditions, '\'' . $value . '\'');
            return $this;
        }
    }

    public function select()
    {
        $this->trace[] = 'select';
        $this->trace['cond'] = '';

        $args = func_get_args();
        $this->query['select'] = $args;
        return $this;
    }

    public function table($table)
    {
        $this->trace[] = 'table';
        $this->trace['cond'] = '';

        $this->query['table'] = $table;
        return $this;
    }

    public function insert($insertData)
    {
        $dbc = $this->getDB();
        $table = $this->query['table'];
        $query = 'INSERT INTO ' . $table;
        $column = '';
        $data = '';
        $param = '';
        $execute = array();
        $count = count($insertData);

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
    }
}