<?php
    include_once("vendor/autoload.php");
    $coverages = glob("coverages/*.php");

    #increase the memory in multiples of 128M in case of memory error
    ini_set('memory_limit', '12800M');

    $final_coverage = new SebastianBergmann\CodeCoverage\CodeCoverage;
    $count = count($coverages);
    $i = 0;
    foreach ($coverages as $coverage_file)
    {
        $i++;
        echo "Processing coverage ($i/$count) from $coverage_file". PHP_EOL;
        require_once($coverage_file);
        $final_coverage->merge($coverage);
    }

    #add the directories where source code files exists
    $final_coverage->filter()->addDirectoryToWhitelist("/var/www/html/");
    
    echo "Generating final report..." . PHP_EOL;
    $report = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
    $report->process($final_coverage,"reports");
    echo "Report generated succesfully". PHP_EOL;
?>
