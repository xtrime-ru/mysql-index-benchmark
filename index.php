<?php

require "database.php";
require "logger.php";

$db = new DataBase();

$tableA = 'employes_a';
$tableB = 'employes_b';
Logger::log('Create tables ...');

$db->createTable($tableA, "INDEX gender_user_id (gender, user_id)");
$db->createTable($tableB, "INDEX user_id_gender (user_id, gender)");

Logger::log('Filling table A ...');
$db->fillTable($tableA, 1_000_000);
Logger::log('Copy rows from table A to B ...');
$db->copyTable($tableA, $tableB);

$queries = [
    "(gender = 'male' AND user_id = 1024)",
    "(gender <> 'female' AND user_id <> 1024)",
    "(gender NOT IN ('male') AND user_id IN (1024, 52048, 812345, 123456))",
    "(gender IN ('female') AND user_id NOT IN (1024, 52048, 812345, 123456))",
    "(user_id IN (-1))",
    "(gender IN ('UNKNOWN'))",
    "(user_id IN (1024, 2048, 12345, 7890123456))",
    "(gender IN ('female'))",
    "(gender IS NOT NULL)",
    "(user_id IS NOT NULL)",
];

function benchmark(DataBase $db, string $table, string $query, int $iterations = 100): void
{
    Logger::log('Start benchmark: ' . $query);

    $timer = -microtime(true);
    foreach (range(1, $iterations) as $_) {
        $db->count($table, $query);
    }
    $timer += microtime(true);
    $avgTime = round($timer / $iterations * 1000, 3);
    Logger::log("Table $table avg requests time: $avgTime ms");

    Logger::log("$table count by query: " . $db->count($table, $query));

    Logger::log("$table explain: " . $db->explain($table, $query));

    Logger::log('Finish benchmark');

    Logger::log('-----------------------------');
}

foreach ($queries as $query) {
    benchmark($db, $tableA, $query, 25);
    benchmark($db, $tableB, $query, 25);
}
