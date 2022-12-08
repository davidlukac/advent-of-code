package main

import (
	"bufio"
	"github.com/davidlukac/advent-of-code/2022/library"
	"sort"
	"strconv"
)

func main() {
	inputFile, closeFn := library.OpenFileFromArgs()
	defer closeFn()

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
