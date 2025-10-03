<?php
// 代码生成时间: 2025-10-03 18:49:51
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Mvc\Model\Query as PhalconQuery;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\Resultset\Simple as ResultsetSimple;

class LogisticsTracking extends Model
{
    /**
     * @Primary
     * @Identity
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $tracking_number;

    /**
     * @Column(type="string")
     */
    protected $status;

    /**
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
     * @Column(type="datetime")
     */
    protected $updated_at;

    /**
     * Initializes model
     */
    public function initialize()
    {
        $this->setSource("logistics_tracking"); // Set the table name
    }

    /**
     * Reads a tracking record by ID
     *
     * @param int $id
     * @return ResultsetSimple
     */
    public static function getTrackingRecord($id)
    {
        try {
            $query = PhalconQuery::fromArray(self::class)
                ->where("id = :id:")
                ->bind(["id" => $id]);

            return $query->execute();
        } catch (Exception $e) {
            // Handle the error
            return new ResultsetSimple([], null, new Phalcon\Mvc\Model\Exception("Error retrieving tracking record: " . $e->getMessage()));
        }
    }

    /**
     * Updates the status of a tracking record
     *
     * @param int $id
     * @param string $newStatus
     * @return bool
     */
    public static function updateStatus($id, $newStatus)
    {
        try {
            $record = self::findFirstByid($id);
            if ($record) {
                $record->status = $newStatus;
                return $record->save();
            } else {
                throw new Exception("Tracking record not found");
            }
        } catch (Exception $e) {
            // Handle the error
            return false;
        }
    }

    /**
     * Retrieves the tracking records
     *
     * @return ResultsetSimple
     */
    public static function listTrackingRecords()
    {
        try {
            $query = PhalconQuery::fromArray(self::class);

            return $query->execute();
        } catch (Exception $e) {
            // Handle the error
            return new ResultsetSimple([], null, new Phalcon\Mvc\Model\Exception("Error retrieving tracking records: " . $e->getMessage()));
        }
    }
}
