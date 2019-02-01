<?php
    include_once("vendor/autoload.php");
    $coverages = glob("coverages/*.json");

    #increase the memory in multiples of 128M in case of memory error
    ini_set('memory_limit', '12800M');

    $final_coverage = new SebastianBergmann\CodeCoverage\CodeCoverage;
    $count = count($coverages);
    $i = 0;

    $final_coverage->filter()->addDirectoryToWhitelist("/var/www/html/");

    foreach ($coverages as $coverage_file)
    {
        $i++;
        echo "Processing coverage ($i/$count) from $coverage_file". PHP_EOL;
        $codecoverageData = json_decode(file_get_contents($coverage_file), JSON_OBJECT_AS_ARRAY);
        $test_name = str_ireplace("coverage-", "", basename($coverage_file,".json"));
        $final_coverage->append($codecoverageData, $test_name);
    }

    echo "Generating final report..." . PHP_EOL;
    $report = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
    $report->process($final_coverage,"reports");
    echo "Report generated succesfully". PHP_EOL;
?>
