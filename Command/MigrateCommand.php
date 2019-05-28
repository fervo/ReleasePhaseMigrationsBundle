<?php

namespace Fervo\ReleasePhaseMigrationsBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Bundle\MigrationsBundle\Command\MigrationsMigrateDoctrineCommand;
use Doctrine\Bundle\MigrationsBundle\Command\Helper\DoctrineCommandHelper;
use Fervo\AdvisoryLocker\AdvisoryLockerFactory;

class MigrateCommand extends MigrationsMigrateDoctrineCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('fervo:release-phase-migrations:migrate')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        // EM and DB options cannot be set at same time
        if (null !== $input->getOption('em') && null !== $input->getOption('db')) {
            throw new InvalidArgumentException('Cannot set both "em" and "db" for command execution.');
        }

        DoctrineCommandHelper::setApplicationHelper($this->getApplication(), $input);

        $conn = $this->getApplication()->getHelperSet()->get('db')->getConnection();
        $locker = AdvisoryLockerFactory::createLocker($conn);

        $lockName = $conn->getDatabase().'.migrations';

        $status = null;

        $locker->performSpinlocked($lockName, function() use ($input, $output, &$status) {
            $status = parent::execute($input, $output);
        }, 1000, 30);

        return $status;
    }
}
