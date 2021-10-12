<?php

/**
 * CSV File Reader
 */
class CsvReader
{
    private $file;
    public function __construct(String $file)
    {
        $this->file = $file;
    }

    public function parse() : array
    {
        if (!file_exists($this->file)) {
            throw new Exception('Input file not found.');
        }

        $type = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));
        $separator =  $type == 'csv' ? "," : "\t";

        // Open a file in read mode ('r')
        $fp = fopen($this->file, "r");
        
        $outputData = [];
        // Read first line and consider it as header
        $header = fgetcsv($fp, 1000, $separator);

        // Number of columns in header
        $columns = count($header);
        $line = join(",", $header);
        $outputData[$line] = array_merge($header, ['count']);

        // Looping through records
        while (($data = fgetcsv($fp, 1000,  $separator)) !== FALSE) {
            $line = join(",", $data);
            //if the record is already exists
            if (!isset($outputData[$line])) {
                $outputData[$line] = $data;
                $outputData[$line][$columns] = 0;
            } else {
                $outputData[$line][$columns]++;
            }
        }
        // release file pointer
        fclose($fp);
        return $outputData;
    }
}
