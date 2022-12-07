package main

import (
	"bufio"
	"errors"
	"fmt"
	"io"
	"os"
	"strings"
)

type Hand int
type Result int

type Round struct {
	Opponent Hand
	Player   Hand
	Score    int
	Result   Result
}

const (
	Rock        Hand   = 1
	Paper       Hand   = 2
	Scissors    Hand   = 3
	Loose       Result = 0
	Draw        Result = 3
	Win         Result = 6
	UnsetInt    int    = -1
	UnsetResult Result = Result(UnsetInt)
	UnsetHand   Hand   = Hand(UnsetInt)
)

var (
	HandMap = map[string]Hand{
		"A": Rock,
		"B": Paper,
		"C": Scissors,
		"X": Rock,
		"Y": Paper,
		"Z": Scissors,
	}
	ResultMap = map[string]Result{
		"X": Loose,
		"Y": Draw,
		"Z": Win,
	}
)

func main() {
	if len(os.Args) < 2 {
		panic("input file not provided")
	}

	inputFilePath := os.Args[1]
	stat, err := os.Stat(inputFilePath)
	if err != nil {
		if errors.Is(err, os.ErrNotExist) {
			panic(fmt.Sprintf("file %s doens't exist", inputFilePath))
		} else {
			panic(fmt.Sprintf("error: %v", err))
		}
	}
	if stat.IsDir() {
		panic("provided path is a directory")
	}

	inputFile, err := os.Open(inputFilePath)
	defer func(inputFile *os.File) {
		err := inputFile.Close()
		if err != nil {
			panic("failed to close input file")
		}
	}(inputFile)

	var game []*Round
	score := 0

	scanner := bufio.NewScanner(inputFile)
	for scanner.Scan() {
		line := scanner.Text()
		round, err := parseLine(line)
		if err != nil {
			panic(err)
		}
		game = append(game, round)
		score += round.Score
	}

	println(score)

	var game2 []*Round
	score = 0

	inputFile.Seek(0, io.SeekStart)
	scanner = bufio.NewScanner(inputFile)
	for scanner.Scan() {
		line := scanner.Text()
		round, err := parseLine2(line)
		if err != nil {
			panic(err)
		}
		game2 = append(game2, round)
		score += round.Score
	}

	println(score)
}

func parseLine(l string) (*Round, error) {
	parts := strings.Split(l, " ")
	if len(parts) != 2 {
		return nil, fmt.Errorf("invalid line: %s", l)
	}
	opponentString := strings.TrimSpace(parts[0])
	playerString := strings.TrimSpace(parts[1])
	opponent, found := HandMap[strings.ToUpper(opponentString)]
	if !found {
		return nil, fmt.Errorf("invalid line: %s", l)
	}
	player, found := HandMap[strings.ToUpper(playerString)]
	if !found {
		return nil, fmt.Errorf("invalid line: %s", l)
	}

	r := Round{Opponent: opponent, Player: player, Score: 0}
	r.CalculateScore()

	return &r, nil
}

func parseLine2(l string) (*Round, error) {
	parts := strings.Split(l, " ")
	if len(parts) != 2 {
		return nil, fmt.Errorf("invalid line: %s", l)
	}
	opponentString := strings.TrimSpace(parts[0])
	playerString := strings.TrimSpace(parts[1])
	opponent, found := HandMap[strings.ToUpper(opponentString)]
	if !found {
		return nil, fmt.Errorf("invalid line: %s", l)
	}
	result, found := ResultMap[strings.ToUpper(playerString)]
	if !found {
		return nil, fmt.Errorf("invalid line: %s", l)
	}

	r := NewRound()
	r.Opponent = opponent
	r.Result = result
	if err := r.FindPlayerHand(); err != nil {
		return r, err
	}
	r.CalculateScore()

	return r, nil
}

func (r *Round) FindPlayerHand() error {
	if r.Opponent == UnsetHand || r.Result == UnsetResult {
		return fmt.Errorf("to find players hand, opponent and result must be already set")
	}

	switch r.Result {
	case Win:
		switch r.Opponent {
		case Rock:
			r.Player = Paper
		case Paper:
			r.Player = Scissors
		case Scissors:
			r.Player = Rock
		}
	case Draw:
		r.Player = r.Opponent
	case Loose:
		switch r.Opponent {
		case Rock:
			r.Player = Scissors
		case Paper:
			r.Player = Rock
		case Scissors:
			r.Player = Paper
		}
	}

	return nil
}

func (r *Round) CalculateScore() {
	score := int(r.Player)

	if r.Player == r.Opponent {
		score += 3
	} else if (r.Player == Rock && r.Opponent == Scissors) ||
		(r.Player == Paper && r.Opponent == Rock) ||
		(r.Player == Scissors && r.Opponent == Paper) {
		score += 6
	}

	r.Score = score
}

func NewRound() *Round {
	newResult := new(Round)
	newResult.Opponent = UnsetHand
	newResult.Result = UnsetResult
	return newResult
}
