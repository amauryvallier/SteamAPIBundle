<?php

namespace AmVal\SteamAPIBundle\Command;

use AmVal\SteamAPIBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProfileCrawlerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('steamapi:crawl-profile')
            ->setDescription('Allows to crawl the Steam API getting data for a profile')
            ->setHelp('This command will crawl the profile & harvest the data of the Steam profile given in parameter')
            ->addArgument('steamID64', InputArgument::REQUIRED, 'The Steam ID of the profile.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '/*********************/',
            '/* Steam API Crawler */',
            '/*********************/',
            '',
            'Profile: ' . $input->getArgument('steamID64'),
            ''
        ]);

        $dataService = $this->getContainer()->get('am_val_steam_api.data_service');
        $storageService = $this->getContainer()->get('am_val_steam_api.storage_service');

        $steamID64 = $input->getArgument('steamID64');
        $games = $dataService->defineGames($steamID64);

        $output->writeln($games['game_count'] . ' games.');
        $output->writeln('');

        $userCollectionDate = $storageService->getLastUserDataCollectionDate($steamID64);
        $userPrivacy = $storageService->getUserPrivacy($steamID64);

        foreach ($games['games'] as $game) {

            $limitDate = new \DateTime("-1 month");
            $gameCollectionDate = $storageService->getLastGameDataCollectionDate($game['appid']);

            $output->writeln("Processing : " . $game['name']);
            if($gameCollectionDate <= $limitDate) {
                $output->writeln('Updating Global Achievements.');
                $dataService->defineGlobalAchievements($game['appid']);
                $output->writeln('Updating Global Stats.');
                $dataService->defineGlobalStats($game['appid']);
            }
            if($userPrivacy == User::PRIVACY_PUBLIC && ($gameCollectionDate <= $limitDate || $userCollectionDate <= $limitDate)) {
                $output->writeln('Updating User Achievements.');
                $dataService->defineUserAchievements($game['appid'], $steamID64);
                $output->writeln('Updating User Stats.');
                $dataService->defineUserStats($game['appid'], $steamID64);
            }
            $storageService->updateGameDataCollectionDate($game['appid']);
            $output->writeln('Done.');
            $output->writeln('');
        }
        $storageService->updateUserDataCollectionDate($steamID64);
    }
}