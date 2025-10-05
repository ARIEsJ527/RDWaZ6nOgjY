<?php
// 代码生成时间: 2025-10-06 02:45:23
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;

class TestDataGenerator extends Model
{

    protected $id;
    protected $name;
    protected $email;
    protected $created_at;
    protected $updated_at;

    /**
     * Generate test data
     *
     * @param int $number_of_records Number of test records to generate
     * @return bool
     */
    public function generateTestData($number_of_records)
    {
        try {
            // Start transaction
            $this->getDI()->getShared('db')->begin();

            for ($i = 1; $i <= $number_of_records; $i++) {
                // Generate random data
                $data = $this->generateRandomData();

                // Create new record
                $record = new TestData();
                $record->assign(array(
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ));

                // Save record
                if (!$record->save()) {
                    $this->getDI()->getShared('db')->rollback();
                    return false;
                }
            }

            // Commit transaction
            $this->getDI()->getShared('db')->commit();
            return true;

        } catch (Exception $e) {
            // Rollback transaction
            $this->getDI()->getShared('db')->rollback();
            // Log error message
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Generate random data for a single record
     *
     * @return array
     */
    private function generateRandomData()
    {
        $faker = Faker\Factory::create();
        return array(
            'name' => $faker->name,
            'email' => $faker->email
        );
    }
}

// Usage example
$generator = new TestDataGenerator();
if ($generator->generateTestData(100)) {
    echo 'Test data generated successfully';
} else {
    echo 'Failed to generate test data';
}
