#!/usr/bin/env php
<?php
require_once('Helpers/PraseArguments.php');
require_once('Helpers/CsvReader.php');
require_once('Helpers/CsvWriter.php');

class Application
{
    protected $argv;

    public function __construct($argv)
    {
        $this->argv = $argv;
    }
    /**
     * Resolve Reader class
     * @param String $file filepath
     */
    protected function resolveParser(String $file)
    {
        $type = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if ($type == 'csv' || $type == 'tsv') {
            return new CsvReader($file);
        } else if ($type == 'json') {
            // New formats could be introduced in the future ie. (json, xml etc).
        } else {
            throw new Exception('Required parser not found.');
        }
    }
    /**
     * Resolve Writer class
     * @param String $file filepath
     */
    protected function resolveWriter(String $file)
    {
        $type = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if ($type == 'csv' || $type == 'tsv') {
            return new CsvWriter($file);
        } else if ($type == 'json') {
            // New formats could be introduced in the future ie. (json, xml etc).
        } else {
            throw new Exception('Required parser not found.');
        }
    }

    public function run(): void
    {
        try {
            // Get command line arguments
            $praseArguments = new PraseArguments($this->argv);
            $file = $praseArguments->getArgument('file');
            $fileUnique = $praseArguments->getArgument('unique-combinations');

            // Resolve suitable Reader for file
            $parser = $this->resolveParser($file);
            $output = $parser->parse();

            // Resolve suitable writer for file
            $writer = $this->resolveWriter($fileUnique);
            $writer->write($output);

        } catch (\Exception $ex) {
            echo "========================\n";
            echo $ex->getMessage() . "\n";
            echo "========================\n";
            die();
        }
    }
};


(new Application($argv))->run();
