<?php

namespace App\Command;

use App\Entity\Fruit;
use App\Entity\Vegetable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

# Usage: php bin/console app:import-data ".\request.json"
class ImportCommand extends Command
{
    public function __construct(
       private EntityManagerInterface $entityManager
    ) {  
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:import-data')
            ->setDescription('Imports JSON file')
            ->addArgument('file_path', InputArgument::REQUIRED, 'File path');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('file_path');
        if (!file_exists($filePath)) { 
            $output->writeln('<error>File missing</error>');
            return Command::FAILURE;
         }

        $contents = file_get_contents($filePath);
        if (!$contents) {
            $output->writeln('<error>Empty file</error>');
            return Command::FAILURE;
        }

        $data = json_decode($contents, true);

        foreach($data as $item){
            if (
                !isset($item['id'])
                || !isset($item['name'])
                || !isset($item['type'])
                || !isset($item['quantity'])
                || !isset($item['unit'])
            ) {
                $output->writeln('<error>Missing attribute</error>');
                return Command::FAILURE;
            }

            $food = null;
            if ('vegetable' === $item['type']) {
                $food = new Vegetable();
            } elseif ('fruit' === $item['type']) {
                $food = new Fruit();
            } else {
                $output->writeln('<error>Unkown type</error>');
                return Command::FAILURE;
            }
            // ID is auto-generated and cannot be set
            // $food->setId($item['id']);

            if ('g' === $item['unit']) {
                $food->setQuantity($item['quantity']);
            } elseif ('kg' === $item['unit']) {
                $food->setQuantity($item['quantity'] * 1000);
            } else {
                $output->writeln('<error>Unkown quantity</error>');
                return Command::FAILURE;
            }
            $food->setName($item['name']);

            $this->entityManager->persist($food);
            $output->writeln($food->getName().' imported');
        }
        
        $this->entityManager->flush();
        $output->writeln('<info>Import successfull</info>');
        return Command::SUCCESS;
    }
}