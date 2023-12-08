<?php

declare(strict_types=1);

namespace OCA\KmaAssignWork\Migration;

use Closure;
use Doctrine\DBAL\Types;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;
use phpDocumentor\Reflection\PseudoTypes\False_;

/**
 * Auto-generated migration step: Please modify to your needs!
 */
class Version1000Date20230419181522 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
     * @return null|ISchemaWrapper
	 */
	public function preSchemaChange(IOutput $output, Closure $schemaClosure, array $options): void {
	}


	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		// work_item
        if (!$schema->hasTable('kma_work_item')){
			$table = $schema->createTable('kma_work_item');
			$table->addColumn('work_id', 'integer', [
				'notnull' => true,
				'length' => 64,
				'autoincrement' => true,
			]);

            $table->addColumn('work_name', 'string', [
				'notnull' => true,
				'length' => 64
			]);

			$table->addColumn('work_description', 'text', [
				'notnull' => true,
				'default' => '',
			]);

			$table->addColumn('level_id', 'integer', [
				'notnull' => true,
				'length' => 64,
				'default' => '',
			]);

            $table->addColumn('status_id', 'integer', [
				'notnull' => false,
				'length' => 64,
				'default' => '',
			]);

            $table->addColumn('user_create', 'string', [
				'notnull' => true,
				'length' => 64,
			]);

            $table->setPrimaryKey(['work_id']);
        }

		// level
        if (!$schema->hasTable('kma_level')){
			$table = $schema->createTable('kma_level');
			$table->addColumn('level_id', 'integer', [
				'notnull' => true,
				'length' => 64,
				'autoincrement' => true,
			]);

			$table->addColumn('level_name', 'string', [
				'notnull' => true,
				'length' => 64,
				'default' => '',
			]);

            $table->addColumn('level_description', 'string', [
				'notnull' => true,
				'default' => '',
			]);
            $table->setPrimaryKey(['level_id']);
        }

		// task_item
        if (!$schema->hasTable('kma_task_item')){
			$table = $schema->createTable('kma_task_item');
			$table->addColumn('task_id', 'integer', [
				'notnull' => true,
				'length' => 64,
				'autoincrement' => true,
			]);

			$table->addColumn('task_name', 'string', [
				'notnull' => true,
				'length' => 64
			]);

			$table->addColumn('task_description', 'text', [
				'notnull' => true,
				'length' => 255
			]);

			$table->addColumn('status_id', 'integer', [
				'notnull' => false,
				'length' => 64,
			]);

            $table->addColumn('work_id', 'integer', [
				'notnull' => true,
				'length' => 64,
			]);

			$table->addColumn('level_id', 'integer', [
				'notnull' => true,
				'length' => 64,
			]);

            $table->addColumn('work_start', 'date', [
				'notnull' => false,
			]);

            $table->addColumn('work_end', 'date', [
				'notnull' => false,
			]);

			$table->addColumn('user_create', 'string', [
				'notnull' => true,
				'length' => 64,
			]);

			$table->addColumn('user_respond', 'string', [
				'notnull' => false,
				'length' => 64,
			]);

			$table->addColumn('user_support', 'string', [
				'notnull' => false,
				'length' => 64,
			]);
            
            $table->setPrimaryKey(['task_id']);
        }

		// status
        if (!$schema->hasTable('kma_status')){
			$table = $schema->createTable('kma_status');
			$table->addColumn('status_id', 'integer', [
				'notnull' => true,
				'length' => 64,
				'autoincrement' => true,
			]);

			$table->addColumn('status_name', 'string', [
				'notnull' => true,
				'length' => 64,
				'default' => '',
			]);

            $table->setPrimaryKey(['status_id']);
        }

		// comment
		if (!$schema->hasTable('kma_comments')){
			$table = $schema->createTable('kma_comments');
			$table->addColumn('comment_id', 'integer', [
				'notnull' => true,
				'length' => 64,
				'autoincrement' => true,
			]);

			$table->addColumn('user_create', 'string', [
				'notnull' => true,
				'length' => 64,
			]);

			$table->addColumn('message', 'text', [
				'notnull' => true,
				'default' => '',
			]);

            $table->setPrimaryKey(['comments_id']);
        }

		if (!$schema->hasTable('kma_connection')){
			$table = $schema->createTable('kma_connection');
			$table->addColumn('connection_id', 'integer', [
				'notnull' => true,
				'length' => 64,
				'autoincrement' => true,
			]);

			$table->addColumn('task_id', 'string', [
				'notnull' => true,
				'length' => 64,
				'default' => '',
			]);

			$table->addColumn('file_id', 'integer', [
				'notnull' => true,
				'length' => 64,
			]);

            $table->setPrimaryKey(['connection_id']);
        }

		return $schema;
    }
    /**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 */
	public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options): void {
	}
}
