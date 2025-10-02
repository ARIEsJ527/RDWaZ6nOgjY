<?php
// 代码生成时间: 2025-10-03 03:30:26
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\Transaction\Manager;
use Phalcon\Mvc\Model\Transaction\Failed;

class MemberPoints extends Model
{
    // 会员ID
    public $member_id;
    // 积分余额
    public $points;
    // 积分流水ID
    public $points_log_id;

    // 获取会员积分
    public function getMemberPoints(int $memberId): int
    {
        try {
            $memberPoints = self::findFirst([
                'conditions' => 'member_id = :member_id:',
                'bind' => ['member_id' => $memberId],
            ]);

            if ($memberPoints) {
                return (int) $memberPoints->points;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            // 错误处理
            throw new Exception('Failed to retrieve member points: ' . $e->getMessage(), $e->getCode());
        }
    }

    // 添加积分
    public function addPoints(int $memberId, int $points, Manager $transactionManager): bool
    {
        try {
            $transaction = $transactionManager->get();
            $pointsLogId = $this->createPointsLog($memberId, $points, $transaction);
            $this->updateMemberPoints($memberId, $points, $transaction);
            $transaction->commit();
            return $pointsLogId > 0;
        } catch (Failed $e) {
            // 回滚事务
            $transaction->rollback();
            throw new Exception('Failed to add points: ' . $e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            // 回滚事务
            $transaction->rollback();
            throw new Exception('Failed to add points: ' . $e->getMessage(), $e->getCode());
        }
    }

    // 创建积分流水
    private function createPointsLog(int $memberId, int $points, Manager $transaction): int
    {
        $pointsLog = new PointsLog();
        $pointsLog->member_id = $memberId;
        $pointsLog->points = $points;
        $pointsLog->status = 1; // 1 for added, -1 for deducted
        return $pointsLog->save($transaction);
    }

    // 更新会员积分
    private function updateMemberPoints(int $memberId, int $points, Manager $transaction): bool
    {
        $member = self::findFirst([
            'conditions' => 'member_id = :member_id:',
            'bind' => ['member_id' => $memberId],
        ]);

        if ($member) {
            $member->points = $member->points + $points;
            return $member->save($transaction);
        } else {
            throw new Exception('Member not found');
        }
    }
}

// 积分流水
class PointsLog extends Model
{
    public $member_id;
    public $points;
    public $status; // 1 for added, -1 for deducted

    public function getSource(): string
    {
        return 'points_log';
    }
}
