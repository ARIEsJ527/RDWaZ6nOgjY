<?php
// 代码生成时间: 2025-09-13 19:25:11
// 数据库迁移工具类
class DatabaseMigrationTool {

    protected $db;
    protected $migrationsDir;
    protected $migrationsTableName = "migrations";

    // 构造函数
    public function __construct($db, $migrationsDir) {
        $this->db = $db;
        $this->migrationsDir = $migrationsDir;
    }

    // 执行迁移
    public function migrate() {
        try {
            // 读取迁移文件
            $migrations = $this->getMigrations();

            // 获取已执行的迁移记录
            $executedMigrations = $this->getExecutedMigrations();

            // 执行未执行的迁移
            $this->executePendingMigrations($migrations, $executedMigrations);

            echo "Migration completed successfully.\
";
        } catch (Exception $e) {
            // 错误处理
            echo "Migration failed: " . $e->getMessage() . "\
";
        }
    }

    // 获取迁移文件
    protected function getMigrations() {
        $migrations = array();
        $dir = new DirectoryIterator($this->migrationsDir);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isFile() && $fileinfo->getExtension() === 'php') {
                $class = $fileinfo->getBasename('.php');
                $migrations[$class] = $this->loadMigrationClass($class);
            }
        }
        ksort($migrations);
        return $migrations;
    }

    // 加载迁移类
    protected function loadMigrationClass($className) {
        if (!class_exists($className)) {
            require_once $this->migrationsDir . DIRECTORY_SEPARATOR . $className . '.php';
        }
        return new $className($this->db);
    }

    // 获取已执行的迁移记录
    protected function getExecutedMigrations() {
        $executedMigrations = array();
        $rows = $this->db->fetchAll("SELECT * FROM {$this->migrationsTableName}");
        foreach ($rows as $row) {
            $executedMigrations[] = $row['migration'];
        }
        return $executedMigrations;
    }

    // 执行未执行的迁移
    protected function executePendingMigrations($migrations, $executedMigrations) {
        foreach ($migrations as $className => $migration) {
            if (!in_array($className, $executedMigrations)) {
                $migration->up();
                $this->logMigration($className);
            }
        }
    }

    // 记录迁移记录
    protected function logMigration($migration) {
        $this->db->insert(
            $this->migrationsTableName,
            array(
                'migration' => $migration,
                'executed_at' => date('Y-m-d H:i:s')
            )
        );
    }
}

// 使用示例
$db = new Phalcon\Db\Adapter\Pdo\Mysql(array(
    'host' => '127.0.0.1',
    'username' => 'root',
    'password' => 'password',
    'dbname' => 'test_db'
));
$migrationTool = new DatabaseMigrationTool($db, "./migrations");
$migrationTool->migrate();

/**
 * 迁移类示例
 */
class MigrationBase {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function up() {
        // 定义迁移操作
    }
}

// 确保迁移文件遵循命名规范，例如：
// V1__initial_migration.php
// V2__add_user_table.php
