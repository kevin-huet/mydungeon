<?php


namespace App\Command;


use App\Entity\WoW\Expansion;
use App\Repository\DungeonDataRepository;
use App\Repository\UserRepository;
use App\Repository\WoW\ExpansionRepository;
use App\Service\Api\WarcraftApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InitExpansionsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected static $defaultName = 'app:blizzard:expansion:init';
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var WarcraftApiService
     */
    private $warcraftApiService;
    /**
     * @var ParameterBagInterface
     */
    private $bag;
    /**
     * @var ExpansionRepository
     */
    private $expansionRepository;
    /**
     * @var DungeonDataRepository
     */
    private $dungeonRepository;

    public function __construct(EntityManagerInterface $entityManager, WarcraftApiService $warcraftApiService,
                                ParameterBagInterface $bag, ExpansionRepository $repository, DungeonDataRepository $dungeonRepository)
    {
        parent::__construct();

        $this->em = $entityManager;
        $this->warcraftApiService = $warcraftApiService;
        $this->bag = $bag;
        $this->expansionRepository = $repository;
        $this->dungeonRepository = $dungeonRepository;

    }

    protected function configure()
    {
    }

    private function expansionDataProcess($expansion_data)
    {
        $array_expansion = [];
        $toEnd = count($expansion_data);
        foreach ($expansion_data as $expansion_tmp) {
            $expansion = $this->expansionRepository->createIfNotExist($expansion_tmp->name, $expansion_tmp->id);
            $expansion->setName($expansion_tmp->name);
            $expansion->setExpansionId($expansion_tmp->id);
            if (0 === --$toEnd)
                $expansion->setLastExpansion(true);
            $this->em->persist($expansion);
            $array_expansion[] = $expansion;
        }
        return $array_expansion;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $expansion_data = $this->warcraftApiService->getExpansions();
        $expansion_data = json_decode($expansion_data)->tiers;
        if ($expansion_data) {
            $expansions = $this->expansionDataProcess($expansion_data);
            $this->dungeonDataProcess($expansions);
            $this->em->flush();
        } else
            $output->writeln("error");

        $output->writeln('Done.');

        return Command::SUCCESS;
    }

    private function dungeonDataProcess(array $expansions)
    {
        /** @var Expansion $expansion */
        foreach ($expansions as $expansion) {
            $instances = $this->warcraftApiService->getInstanceExpansions($expansion->getExpansionId());
            $instances = json_decode($instances);
            foreach ($instances->dungeons as $instance) {
                $dungeon = $this->dungeonRepository->createIfNotExist($instance->name, $expansion->getId());
                $dungeon->setName($instance->name);
                $dungeon->setExpansion($expansion);
                $dungeon->setMedia($this->getInstanceMedia($instance->id));
                $expansion->addDungeon($dungeon);
                $this->em->persist($dungeon);
                $this->em->persist($expansion);
            }
        }
    }

    private function getInstanceMedia($id)
    {
        $data = $this->warcraftApiService->getInstanceMedia($id);
        $data = json_decode($data);
        foreach ($data->assets as $asset) {
            if (isset($asset->value))
                return $asset->value;
        }
        return null;
    }
}