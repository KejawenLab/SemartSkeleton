<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Command;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Generator\GeneratorFactory;
use KejawenLab\Semart\Skeleton\Menu\MenuService;
use PHLAK\Twine\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorCommand extends Command
{
    private const NAMESPACE = 'KejawenLab\Semart\Skeleton\Entity';

    private $generatorFactory;

    private $menuService;

    public function __construct(GeneratorFactory $generatorFactory, MenuService $menuService)
    {
        $this->generatorFactory = $generatorFactory;
        $this->menuService = $menuService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(sprintf('%s:crud:generate', Application::APP_UNIQUE_NAME))
            ->setAliases([sprintf('%s:generate', Application::APP_UNIQUE_NAME), sprintf('%s:gen', Application::APP_UNIQUE_NAME)])
            ->setDescription('Generate Simpel CRUD')
            ->setHelp('Generate Simpel CRUD')
            ->addArgument('entity', InputArgument::REQUIRED)
            ->addOption('parent', 'p', InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('<fg=green;options=bold>
   _____                           __     ______                           __
  / ___/___  ____ ___  ____ ______/ /_   / ____/__  ____  ___  _________ _/ /_____  _____
  \__ \/ _ \/ __ `__ \/ __ `/ ___/ __/  / / __/ _ \/ __ \/ _ \/ ___/ __ `/ __/ __ \/ ___/
 ___/ /  __/ / / / / / /_/ / /  / /_   / /_/ /  __/ / / /  __/ /  / /_/ / /_/ /_/ / /
/____/\___/_/ /_/ /_/\__,_/_/   \__/   \____/\___/_/ /_/\___/_/   \__,_/\__/\____/_/

<comment>By: KejawenLab - Muhamad Surya Iksanudin<surya.kejawen@gmail.com></comment>

</>');
        /** @var string $entity */
        $entity = $input->getArgument('entity');
        $reflection = new \ReflectionClass(sprintf('%s\%s', self::NAMESPACE, $entity));

        $output->writeln('<info>Running Semart Schema Updater</info>');
        /** @var \Symfony\Component\Console\Application $application */
        $application = $this->getApplication();
        $migration = $application->find('doctrine:schema:update');
        $migration->run(new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
            '--no-interaction' => true,
        ]), $output);

        $output->writeln('<info>Running Semart CRUD Generator</info>');
        $this->generatorFactory->generate($reflection);

        $parentMenu = $input->getOption('parent');
        if ($parentMenu) {
            $menuCode = Str::make($reflection->getShortName())->uppercase()->__toString();

            $menu = $this->menuService->getMenuByCode($menuCode);
            $parent = $this->menuService->getMenuByCode($parentMenu);
            if ($menu && $parent) {
                $menu->setParent($parent);

                $this->menuService->addMenu($menu);
            }
        }

        $output->writeln(sprintf('<comment>Simple CRUD for %s class is generated</comment>', $reflection->getName()));

        return 0;
    }
}
