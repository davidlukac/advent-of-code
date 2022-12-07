package main

import (
	"bufio"
	"errors"
	"fmt"
	"os"
	"sort"
	"strconv"
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

	var elves []int
	elves = append(elves, 0)

	scanner := bufio.NewScanner(inputFile)
	for scanner.Scan() {
		line := scanner.Text()
		calories, err := strconv.Atoi(line)
		if err != nil {
			elves = append(elves, 0)
		} else {
			elves[len(elves)-1] = elves[len(elves)-1] + calories
		}
	}

	sort.Ints(elves)
	println(elves[len(elves)-1])
	println(elves[len(elves)-1] + elves[len(elves)-2] + elves[len(elves)-3])
}
