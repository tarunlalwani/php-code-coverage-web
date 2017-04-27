<?php
    $current_dir = __DIR__;
    require_once "$current_dir/vendor/autoload.php";

    echo "Current dir is $current_dir";

    $coverage = new SebastianBergmann\CodeCoverage\CodeCoverage;
    $filter = $coverage->filter();
    $filter->addDirectoryToWhitelist("/var/www/html");


    $test_name = (isset($_COOKIE['test_name']) && !empty($_COOKIE['test_name'])) ? $_COOKIE['test_name'] : 'unknown_test_' . time();
    $coverage->start($test_name);

    function end_coverage()
    {
        global $test_name;
        global $coverage;
        global $filter;
        global $current_dir;
        $coverageName = $current_dir . '/coverages/coverage-' . $test_name . '-' . microtime(true);

        try {
            $coverage->stop();
            $writer = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
            $writer->process($coverage, $current_dir . '/report/');
            $writer = new SebastianBergmann\CodeCoverage\Report\PHP();
            $coverageName = $current_dir . '/coverages/coverage-' . $test_name . '-' . microtime(true);
            $writer->process($coverage, $coverageName . '.php');
        } catch (Exception $ex) {
            file_put_contents($coverageName . '.ex', $ex);
        }
    }

    class coverage_dumper
    {
        function __destruct()
        {
            try {
                end_coverage();
            } catch (Exception $ex) {
                echo str($ex);
            }
        }
    }

    $_coverage_dumper = new coverage_dumper();
#}
