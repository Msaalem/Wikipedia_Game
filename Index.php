<?php


require_once 'Player.php';
require_once 'Game.php';
require_once 'Wiki.php';

// Запрос количества игроков и их имен
echo "Введите количество игроков: ";
$numPlayers = (int)readline();

$playerNames = [];
for ($i = 1; $i <= $numPlayers; $i++) {
    echo "Введите имя для игрока номер $i: ";
    $playerNames[] = readline();
}

// Создание объекта игры
$game = new Game($playerNames);

// Начало игры
while (!$game->isAllPlayersFinished()) {

    for ($i = 0; $i < $numPlayers; $i++) {

        if ($game->isPlayerFinished($i)) {
            continue; // Игрок уже закончил игру
        }

        echo "\nХод игрока {$playerNames[$i]}\n";
        echo "Текущая страница: {$game->getPlayerCurrentPage($i)}\n";
        echo "Доступные страницы: \n";

        $availablePages = $game->getPlayerAvailablePages($i);
        foreach ($availablePages as $key => $page) {
            echo ($key+1) . " - {$page['title']}\n";
        }

        echo "Введите номер страницы для перехода: ";
        $chosenPage = (int)readline();

        $game->makeMove($i, $availablePages[$chosenPage-1]);
    }
}

// Вывод результатов игры
echo "Победитель: {$game->getWinner()->getName()}!\n";
echo "Результаты:\n";
for ($i = 0; $i < $numPlayers; $i++) {
    echo "{$playerNames[$i]}: {$game->getPlayerNumMoves($i)} переходов\n";
}
