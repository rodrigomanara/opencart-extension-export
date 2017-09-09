<?php

namespace Rmanara\Export;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class CommandExport extends Command {

    protected $path;

    /**
     * 
     * @param type $path
     */
    public function __setPath($path) {
        $this->path = $this->getDir($path);
    }

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

        $dir = $this->path . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;



        $finder = new Finder();
        $finder->files()->in($dir);
        $finder->files()->name("*$name*");


        $output->writeln("<comment>===== start +++</comment>");

        $count = 0;
        foreach ($finder as $file) {
            $filename = $file->getfilename();
            $path = $file->getRelativePath();

            if ($this->builder($path, $file->getRealPath(), $filename, $output)) {
                $output->writeln("<info>file $filename was copied to $path</info>");

                $count++;
            }
        }
        $output->writeln("<comment>===== end +++</comment>");
    }
    /**
     * 
     * @param type $path
     * @param type $copy
     * @param type $filename
     * @param OutputInterface $output
     * @return type
     */
    private function Builder($path, $copy, $filename, OutputInterface $output) {
        $temp = $this->path . "temp_extension" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $path;

        if (!is_dir($temp)) {
            if (mkdir($temp, 655, true)) {
                $output->writeln("final path $temp");
            }
        }
        $temp_path = $temp . DIRECTORY_SEPARATOR . $filename;
        return copy($copy, $temp_path);
    }

    /**
     * 
     * @param type $dir
     * @return type
     */
    private function getDir($dir) {

        preg_match("/(.*?)upload/", $dir, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return $dir;
    }

}
