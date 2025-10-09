<?php
// 代码生成时间: 2025-10-09 13:47:08
// 引入Phalcon框架的核心类
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Mvc\Model\Transaction;

// 财务管理模块
class FinancialManagement extends Model
{
    // 声明属性
    public $id;
    public $description;
    public $amount;
    public $date;
    public $type;
    public $created_at;
    public $updated_at;

    // 构造函数
    public function initialize()
    {
        // 定义字段映射
        $this->setSource('financial_records');
    }

    // 自动完成时间戳
    public function beforeValidationOnCreate()
    {
        if (empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }
    }

    // 更新时间戳
    public function beforeValidationOnUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    // 添加财务记录
    public function addRecord($data): bool
    {
        try {
            // 开启事务
            $transaction = $this->getDI()->getShared('transactionManager')->get();
            // 验证数据
            if (!$this->isValid($data, $this)) {
                $transaction->rollback();
                return false;
            }
            // 插入数据
            $result = $transaction->execute(["INSERT INTO financial_records (description, amount, date, type, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)", 
                [$data['description'], $data['amount'], $data['date'], $data['type'], $data['created_at'], $data['updated_at']]], Transaction::TYPE_NONSAFE);
            // 提交事务
            if ($result) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollback();
                return false;
            }
        } catch (Failed $e) {
            // 错误处理
            $this->appendMessage(new Message("Failed to add financial record: " . $e->getMessage()));
            return false;
        }
    }

    // 更新财务记录
    public function updateRecord($id, $data): bool
    {
        try {
            // 开启事务
            $transaction = $this->getDI()->getShared('transactionManager')->get();
            // 更新数据
            $result = $transaction->execute(["UPDATE financial_records SET description = ?, amount = ?, date = ?, type = ?, updated_at = ? WHERE id = ?", 
                [$data['description'], $data['amount'], $data['date'], $data['type'], $data['updated_at'], $id]], Transaction::TYPE_NONSAFE);
            // 提交事务
            if ($result) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollback();
                return false;
            }
        } catch (Failed $e) {
            // 错误处理
            $this->appendMessage(new Message("Failed to update financial record: " . $e->getMessage()));
            return false;
        }
    }

    // 删除财务记录
    public function deleteRecord($id): bool
    {
        try {
            // 开启事务
            $transaction = $this->getDI()->getShared('transactionManager')->get();
            // 删除数据
            $result = $transaction->execute(["DELETE FROM financial_records WHERE id = ?", [$id]], Transaction::TYPE_NONSAFE);
            // 提交事务
            if ($result) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollback();
                return false;
            }
        } catch (Failed $e) {
            // 错误处理
            $this->appendMessage(new Message("Failed to delete financial record: " . $e->getMessage()));
            return false;
        }
    }

    // 验证数据
    private function isValid($data, Model $model): bool
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $model->appendMessage(new Message("The $key is required."));
                return false;
            }
        }
        return true;
    }
}
