<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Command;

use KejawenLab\Semart\Skeleton\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class InstallationCommand extends Command
{
    private $semart;

    public function __construct(KernelInterface $kernel)
    {
        $this->semart = sprintf('%s%s.semart', $kernel->getProjectDir(), \DIRECTORY_SEPARATOR);

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(sprintf('%s:install', Application::APP_UNIQUE_NAME))
            ->setDescription('Install Semart Application Skeleton')
            ->setHelp('Install Semart Application Skeleton')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileSystem = new Filesystem();
        if ($fileSystem->exists($this->semart)) {
            return 0;
        }

        $output->writeln('<options=underscore>SEMART SKELETON INSTALLATION</>');
        $output->writeln('<comment>===========================================================</comment>');

        $output->writeln('<comment>===========================================================</comment>');
        $output->writeln('<options=bold>Creating new Semart Application database</>');
        $output->writeln('<comment>===========================================================</comment>');
        /** @var \Symfony\Component\Console\Application $application */
        $application = $this->getApplication();
        $dropDatabase = $application->find('doctrine:database:drop');
        $dropDatabase->run(new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => true,
        ]), $output);

        $createDatabase = $application->find('doctrine:database:create');
        $createDatabase->run(new ArrayInput([
            'command' => 'doctrine:database:create',
        ]), $output);

        $output->writeln('<info>Running Semart Schema Updater</info>');
        $input = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
            '--no-interaction' => true,
        ]);
        $input->setInteractive(false);
        $migration = $application->find('doctrine:schema:update');
        $migration->run($input, $output);

        $output->writeln('<info>Loading Semart Application initial data</info>');
        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => true,
        ]);
        $input->setInteractive(false);
        $fixtures = $application->find('doctrine:fixtures:load');
        $fixtures->run($input, $output);

        $fileSystem->dumpFile($this->semart, (string) 0);

        return 0;
    }
}
