<?php

namespace Rmanara\Export;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class CommandExport extends Command {

    /**
     * configuration, setting the commands
     */
    protected function configure() {
        $this
                ->setName('app:export')
                ->setDescription('export and zipped files to opencart extensions models')
                ->addArgument('filename', InputArgument::REQUIRED, 'find all files begin|has with that name')

        ;
    }

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        $name = $input->getArgument('filename');

        $dir = __DIR__ . "/../../../upload";


        $finder = new Finder();
        $finder->files()->in($dir);
        $finder->files()->name("*$name*");


        $output->writeln("===== start +++");

        $count = 0;
        foreach ($finder as $file) {
            $filename = $file->getfilename();
            $path = $file->getRelativePath();
            if ($this->builder($path, $file->getRealPath(), $filename)) {
                $output->writeln("file $filename was copied to $path");

                $count++;
            }
        }

       

        $output->writeln("===== end +++");
    }

    private function Builder($path, $copy, $filename) {
        $temp = __DIR__ . "/upload/" . $path;

        if (!is_dir($temp)) {
            mkdir($temp, 655, true);
        }
        $temp_path = $temp . DIRECTORY_SEPARATOR . $filename;
        return copy($copy, $temp_path);
    }

}
