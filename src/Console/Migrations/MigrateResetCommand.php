<?php

namespace Vinelab\NeoEloquent\Console\Migrations;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class MigrateResetCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $name = 'neo4j:migrate:reset';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Rollback all database migrations';

    /**
     * The migrator instance.
     *
     * @var \Vinelab\NeoEloquent\Migrations\Migrator
     */
    protected $migrator;

    /**
     * @param \Vinelab\NeoEloquent\Migrations\Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(): int
    {
        $this->migrator->setOutput($this->output);

        $this->migrator->setConnection($this->input->getOption('database'));

        $pretend = $this->input->getOption('pretend');

        while (true) {
            $count = $this->migrator->rollback(['pretend' => $pretend]);

            if ($count == 0) {
                break;
            }
        }

        return Command::SUCCESS;
    }

    /**
     * {@inheritDoc}
     */
    protected function getOptions()
    {
        return array(
            array('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'),

            array('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'),

            array('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'),
        );
    }
}
