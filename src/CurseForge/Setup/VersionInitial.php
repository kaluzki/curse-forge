<?php

namespace kaluzki\CurseForge\Setup;

require_once dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use fn;

/**
 * @see config/migrations.yml
 */
class VersionInitial extends AbstractMigration
{
    /**
     * @var array[]
     */
    const SCHEMA = [
        'communities' => [
            'id' => Type::INTEGER,
            'name' => Type::STRING,
            'icon' => Type::STRING,
            'image' => Type::STRING,
        ],
        'categories' => [
            'id' => Type::INTEGER,
            'name' => Type::STRING,
            'icon' => Type::STRING,
        ],

    ];

    /**
     * @inheritdoc
     */
    public function up(Schema $schema)
    {
        fn\map(self::SCHEMA, function (array $columns, $tableName) use ($schema) {
            $table = $schema->createTable($tableName);
            fn\map($columns, function ($type, $column) use ($table) {
                $table->addColumn($column, $type);
            });
        });
    }

    /**
     * @inheritdoc
     */
    public function down(Schema $schema)
    {
        $this->up($revert = new Schema());
        $this->addSql($revert->toDropSql($this->platform));
    }
}
