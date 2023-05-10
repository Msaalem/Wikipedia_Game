<?php

class Game {

    private int $pageChoiceCount = 2;   // настройка - по сколько страниц на выбор показывать
    private array $players = array();   // массив игроков

    // конструктор
    public function __construct($playerNames) {
        // создание игроков и добавление их в массив
        foreach ($playerNames as $name) {
            $player = new Player($name);
            $this->players[] = $player;
        }
    }

    // метод проверки игрока на приход к финишу
    public function isPlayerFinished($index) {
        return $this->players[$index]->isFinished();
    }

    // метод получения текущей страницы, на которой находится игрок
    public function getPlayerCurrentPage($index) {
        return $this->players[$index]->getCurrentPage();
    }

    // метод получения списка доступных для перехода страниц
    public function getPlayerAvailablePages($index) {
        return $this->players[$index]->getAvailablePages($this->pageChoiceCount);
    }

    // метод хода игрока
    public function makeMove($index, $page) {
        $this->players[$index]->goToPage($page);
    }

    // метод получения количества сделанных игроком ходов
    public function getPlayerNumMoves($index) {
        return $this->players[$index]->getMovesCount();
    }

    // метод нахождения победителя
    public function getWinner() {

        $winner = $this->players[0];
        $minMoves = $this->players[0]->getMovesCount();

        foreach ( $this->players as $player ) {
            if($player->getMovesCount() < $minMoves) {
                $winner = $player;
                $minMoves = $player->getMovesCount();
            }
        }

        return $winner;
    }

    // метод для проверки, все ли игроки достигли финишной страницы
    public function isAllPlayersFinished(): bool
    {
        foreach ($this->players as $player) {
            if (!$player->isFinished()) {
                return false;
            }
        }
        return true;
    }
}
