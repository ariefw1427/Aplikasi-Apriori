<?php
class DB
{
    var $conn = null;
    public $insert_id = 0;

    public function __construct($host, $username, $passwd, $dbname)
    {
        $this->conn = mysqli_connect($host, $username, $passwd, $dbname);
    }

    public function query($sql)
    {
        $query = mysqli_query($this->conn, $sql) or die('<pre>Error mysqli_query: ' . mysqli_error($this->conn) . '<br />' . $sql . '</pre>');

        if (preg_match("/^(insert|replace)\s+/i", $sql)) {
            $this->insert_id = @$this->conn->insert_id;
        }

        return $query;
    }

    public function multi_query($table, $data)
    {
        $values = array();

        $fields = array_keys(current($data));

        foreach ($data as $key => $val) {
            $values[] = "('" . implode("','", $val) . "')";
        }

        $indeks = 0;
        $max = 200;
        $arr_values = array();
        foreach ($values as $key => $val) {
            $arr_values[$indeks][] = $val;
            if (count($arr_values[$indeks]) >= $max)
                $indeks++;
        }

        foreach ($arr_values as $key => $val) {
            $this->query("INSERT INTO $table (" . implode(",", $fields) . ") VALUES " . implode(",", $val));
        }
    }

    public function get_row($sql)
    {
        $query = $this->query($sql);
        return mysqli_fetch_object($query);
    }

    public function get_results($sql)
    {
        $query = $this->query($sql);
        $arr = array();
        while ($row = mysqli_fetch_object($query)) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_var($sql)
    {
        $query = $this->query($sql);
        $row = mysqli_fetch_row($query);
        return $row[0];
    }
}
