<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Command;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Util\Encryptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class InstallationCommand extends Command
{
    private $envTemplate;

    private $env;

    public function __construct(KernelInterface $kernel)
    {
        $this->envTemplate = sprintf('%s%s.env.template', $kernel->getProjectDir(), \DIRECTORY_SEPARATOR);
        $this->env = sprintf('%s%s.env', $kernel->getProjectDir(), \DIRECTORY_SEPARATOR);

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
        $output->writeln('<options=underscore>SEMART SKELETON INSTALLATION</>');
        $output->writeln('<comment>===========================================================</comment>');

        $output->writeln('<comment>===========================================================</comment>');
        $output->writeln('<options=bold>Checking Environment Variable File</>');
        $output->writeln('<comment>===========================================================</comment>');
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($this->env)) {
            $output->writeln('<info>Creating new environment variable file</info>');
            $this->createEnvironment($input, $output);
        } else {
            $output->writeln('<info>Environment variable file is already exist</info>');
        }

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

        $output->writeln('<comment>===========================================================</comment>');
        $output->writeln('<options=bold>Semart Application Installation is finished</>');
        $output->writeln('<comment>===========================================================</comment>');
        $output->writeln('<comment>Run <info>php bin/console server:run</info> to start your server</comment>');
        $output->writeln('<comment>Login with username: <info>admin</info> and password: <info>semartadmin</info></comment>');

        return 0;
    }

    private function createEnvironment(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('<options=bold>Environment Setup</>');
        $output->writeln('<comment>===========================================================</comment>');
        $helper = $this->getHelper('question');

        $question = new Question('Please enter your application environment [default: <info>dev</info>]: ', 'dev');
        $environment = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your redis url [default: <info>localhost</info>]: ', 'localhost');
        $redisUlr = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your database driver [default: <info>mysql</info>]: ', 'mysql');
        $dbDriver = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your database version [default: <info>5.7</info>]: ', '5.7');
        $dbVersion = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your database charset [default: <info>utf8mb4</info>]: ', 'utf8mb4');
        $dbCharset = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your database user [default: <info>root</info>]: ', 'root');
        $dbUser = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your database password [default: <info>null</info>]: ', null);
        $dbPassword = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your database name [default: <info>semart_app</info>]: ', 'semart_app');
        $dbName = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your database host [default: <info>localhost</info>]: ', 'localhost');
        $dbHost = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your database port [default: <info>3306</info>]: ', 3306);
        $dbPort = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your application short title [default: <info>SEMART SKELETON</info>]: ', 'SEMART SKELETON');
        $appShort = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your application long title [default: <info>KEJAWENLAB APPLICATION SKELETON</info>]: ', 'KEJAWENLAB APPLICATION SKELETON');
        $appLong = $helper->ask($input, $output, $question);

        $question = new Question('Please enter your application version [default: <info>1@dev</info>]: ', '1@dev');
        $appVersion = $helper->ask($input, $output, $question);

        $search = [
            '{{ENV}}',
            '{{SECRET}}',
            '{{REDIS_URL}}',
            '{{DB_DRIVER}}',
            '{{DB_VERSION}}',
            '{{DB_CHARSET}}',
            '{{DB_USER}}',
            '{{DB_PASSWORD}}',
            '{{DB_NAME}}',
            '{{DB_HOST}}',
            '{{DB_PORT}}',
            '{{APP_SHORT}}',
            '{{APP_LONG}}',
            '{{APP_VERSION}}',
        ];

        $secret = Encryptor::encrypt(sprintf('%s:%s', Application::APP_UNIQUE_NAME, date('YmdHis')), Application::APP_UNIQUE_NAME);
        $replace = [$environment, $secret, $redisUlr, $dbDriver, $dbVersion, $dbCharset, $dbUser, Encryptor::encrypt((string) $dbPassword, $secret), $dbName, $dbHost, $dbPort, $appShort, $appLong, $appVersion];

        $envString = str_replace($search, $replace, (string) file_get_contents($this->envTemplate));

        $output->writeln('<options=bold>Dumping Environment Variables</>');

        $this->dumpEnv($envString);
    }

    private function dumpEnv(string $envConent): void
    {
        $fileSystem = new Filesystem();
        $dotenv = new Dotenv();
        $fileSystem->dumpFile($this->env, $envConent);
        $dotenv->load($this->env);
    }
}
