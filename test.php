<?php

include 'vendor/autoload.php';

use voku\helper\UTF8;

echo print_r(mb_detect_order(), true);

$files = [
    'src/NS/ImportBundle/Tests/Fixtures/DobAdmDate.csv',
    'src/NS/ImportBundle/Tests/Fixtures/EMR-IBD-headers.csv',
    'src/NS/ImportBundle/Tests/Fixtures/EMR-IBD-headers-utf16.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-BadDate.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-BadHeader.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-CasePlusRRL.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-CasePlusRRL-DatesInRange.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-CasePlusRRL-FutureDate.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-CasePlusRRL-WithWarning.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-CasePlusSiteLab.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-DuplicateRows.csv',
    'src/NS/ImportBundle/Tests/Fixtures/IBD-PreProcess.csv',
    'src/NS/ImportBundle/Tests/Fixtures/ReaderOffset.csv',
    'src/NS/ImportBundle/Tests/Fixtures/WHO-Binary.csv',
    'src/NS/ImportBundle/Tests/Fixtures/AMRO_MeninHospCaseBased_122016.csv',
    'src/NS/ImportBundle/Tests/Fixtures/AMRO_NeumoHospCaseBased_122016.csv',
    'src/NS/ImportBundle/Tests/Fixtures/AMRO_NeumoMeninLNRCaseBased_122016.csv',
    'src/NS/ImportBundle/Tests/Fixtures/AMRO_RotaHospCaseBased_122016.csv',
    'src/NS/ImportBundle/Tests/Fixtures/AMRO_RotaLNRCaseBased_122016.csv',

];

mb_detect_order(['UTF-8', 'UTF-16', 'ISO-8859-1', 'ASCII']);

foreach ($files as $file) {
    $contents = file_get_contents($file);
    $utf8 = UTF8::file_get_contents($file);
    echo "$file \n\tmb_detect: " . mb_detect_encoding($contents) . "\n\tmb_detect: ".mb_detect_encoding($utf8)."\n";
    unset($contents);
    unset($utf8);
}

