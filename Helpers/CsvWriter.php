<?php

/**
 * CSV File Reader
 */
class CsvWriter
{
    protected $output;
    public function __construct($output)
    {
        $this->output = $output;
    }

    public function write(array $data)
    {
        if (empty($this->output)) {
            throw new Exception('Output file cant be empty.');
        }
        // Get file extension
        $type = strtolower(pathinfo($this->output, PATHINFO_EXTENSION));
        $separator =  $type == 'csv' ? "," : "\t";

        // Open a file in write mode 
        $fp = fopen($this->output, 'w+');
        // Loop through file pointer and a line
        foreach ($data as $row) {
            try {
                fputcsv($fp, $row,   $separator);
            } catch (\Exception $ex) {
                echo "Ignored Row:" . join(',', $row);
                echo "Reason:" .  $ex->getMessage();
            }
        }
        // Release file pointer
        fclose($fp);
    }
}
