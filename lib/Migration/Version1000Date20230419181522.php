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

        if (!$schema->hasTable('kma_work')){
			$table = $schema->createTable('kma_work');
			$table->addColumn('kma_work_id', 'string', [
				'notnull' => true,
				'length' => 64,
				'default' => '',
			]);

            $table->addColumn('work_name', 'string', [
				'notnull' => true,
				'length' => 64
			]);

			$table->addColumn('detail', 'text', [
				'notnull' => true,
				'default' => '',
			]);

            $table->addColumn('level', 'string', [
				'notnull' => true,
				'length' => 64,
			]);

            $table->addColumn('status', 'string', [
				'notnull' => false,
				'length' => 64,
			]);

            $table->addColumn('progress', 'string', [
				'notnull' => false,
				'length' => 64,
			]);

            $table->addColumn('assignment_time', 'date', [
				'notnull' => false,
			]);

            $table->addColumn('end_time', 'date', [
				'notnull' => false,
			]);

            $table->addColumn('assigned_person_id', 'string', [
				'notnull' => true,
				'length' => 64,
			]);

            $table->addColumn('supporter_id', 'string', [
				'notnull' => false,
				'length' => 64,
			]);

            $table->setPrimaryKey(['kma_work_id']);
        }

        if (!$schema->hasTable('work_level')){
			$table = $schema->createTable('work_level');
			$table->addColumn('kma_level_id', 'string', [
				'notnull' => true,
				'length' => 64,
				'default' => '',
			]);

            $table->addColumn('description', 'string', [
				'notnull' => true,
				'length' => 255
			]);
            $table->setPrimaryKey(['kma_level_id']);
        }

        if (!$schema->hasTable('kma_task_in_work')){
			$table = $schema->createTable('kma_task_in_work');
			$table->addColumn('kma_task_id', 'string', [
				'notnull' => true,
				'length' => 64,
				'default' => '',
			]);

            $table->addColumn('kma_work_id', 'string', [
				'notnull' => true,
				'length' => 64,
			]);

            $table->addColumn('task_name', 'string', [
				'notnull' => true,
				'length' => 64
			]);

            $table->addColumn('content', 'string', [
				'notnull' => true,
				'length' => 255
			]);

            $table->addColumn('status', 'string', [
				'notnull' => false,
				'length' => 64,
			]);
            $table->setPrimaryKey(['kma_task_id']);
        }

    }
    /**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 */
	public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options): void {
	}
}
