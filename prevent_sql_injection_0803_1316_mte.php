<?php
// 代码生成时间: 2025-08-03 13:16:27
use Phalcon\Db\Adapter\Pdo\Sqlite as SqliteDb;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as ResultsetSimple;

class PreventSqlInjectionController extends Phalcon\Mvc\Controller
{

    private function getDb()
    {
        // Return a new instance of the Sqlite connection.
        // In production, you would use a different database adapter.
        return new SqliteDb(array(
            'dbname' => 'your_database.db'
        ));
    }

    public function indexAction()
    {
        try {
            // Get the search parameter from the query string.
            $search = $this->request->getQuery('search', Phalcon\Filter::FILTER_STRIPTAGS);

            // Query the database using bound parameters to prevent SQL injection.
            $resultset = $this->findUsers($search);

            // Check if any results were returned.
            if ($resultset) {
                $this->view->setVar('users', $resultset);
            } else {
                $this->flash->error('No users found.');
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }

    private function findUsers($search): ResultsetSimple
    {
        // Initialize the database connection.
        $db = $this->getDb();

        // Prepare the query with bound parameters.
        $sql = 'SELECT * FROM users WHERE name LIKE :search:';

        // Execute the query.
        $resultset = $db->fetchAll($sql, 
            ['search' => '%' . $search . '%']
        );

        return $resultset;
    }
}
