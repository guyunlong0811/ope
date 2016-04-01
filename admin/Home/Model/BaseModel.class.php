<?php
namespace Home\Model;

use Think\Model;

class BaseModel extends Model
{

    //SQL
    public function ExecuteData($sql)
    {
        if (false === $row = $this->execute($sql)) {
            C('G_ERROR', 'db_error');
            return false;
        } else {
            save_sql($this->getLastSql());
            return $row;
        }
    }

    //清空表
    public function TruncateTable($table)
    {
        $sql = "truncate `{$table}`";
        save_sql($sql);
        return $this->ExecuteData($sql);
    }

    //新增
    public function CreateData($data)
    {
        if (!$this->create($data, 1)) {
            C('G_ERROR', $this->getError());
            return false;
        } else {
            if (!$id = $this->add()) {
                C('G_ERROR', 'db_error');
                return false;
            } else {
                save_sql($this->getLastSql());
                return $id;
            }
        }
    }

    //新增
    public function CreateAllData($data)
    {
        if (false === $this->addAll($data)) {
            C('G_ERROR', 'db_error');
            return false;
        } else {
            save_sql($this->getLastSql());
            return true;
        }
    }

    //修改
    public function UpdateData($data, $where = null)
    {

        if (!$this->create($data, 2)) {
            C('G_ERROR', $this->getError());
            return false;
        } else {
            if (empty($where)) {
                $row = $this->save();
            } else {
                $row = $this->where($where)->save();
            }

            if ($row === false) {
                C('G_ERROR', 'db_error');
                return false;
            } else {
                save_sql($this->getLastSql());
                return $row;
            }
        }
    }

    //字段增加
    public function IncreaseData($where, $field, $count = 1)
    {
        if ($count == 0) {
            return true;
        }
        $data[$field] = array('exp', "`{$field}`+{$count}");
        return $this->UpdateData($data, $where);
    }

    //字段减少
    public function DecreaseData($where, $field, $count = 1)
    {
        if ($count == 0) {
            return true;
        }
        $data[$field] = array('exp', "`{$field}`-{$count}");
        return $this->UpdateData($data, $where);
    }

    //删除单条数据
    public function DeleteData($where)
    {

        if (is_array($where)) {
            $row = $this->where($where)->limit(1)->delete();
        } else if ($where > 0) {
            $row = $this->limit(1)->delete($where);
        } else {
            return false;
        }

        if ($row === false) {
            C('G_ERROR', 'db_error');
            return false;
        } else {
            save_sql($this->getLastSql());
            return $row;
        }
    }

    //删除批量数据
    public function DeleteList($where)
    {
        if (false === $row = $this->where($where)->delete()) {
            C('G_ERROR', 'db_error');
            return false;
        } else {
            save_sql($this->getLastSql());
            return $row;
        }
    }

    //获取数据属性
    public function getRowCondition($where, $field = null)
    {
        $data = $this->field($field)->where($where)->find();
        if (!$data) {
            return false;
        }
        return $data;
    }

    //修改表名
    public function CopyGTable($table, $new = null)
    {
        //新表名
        $new = $new == null ? 'c_' . $table . '_' . time2format(null, 6) : $new;
        //修改表名
        $sql = "ALTER TABLE `{$table}` RENAME TO `{$new}`";
        if (false === $this->ExecuteData($sql)) {
            return false;
        }
        //创建重新G空表
        $sql = "CREATE TABLE `{$table}` LIKE `{$new}`";
        if (false === $this->ExecuteData($sql)) {
            return false;
        }
        return true;
    }

}