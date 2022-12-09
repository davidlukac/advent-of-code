package main

import (
	"bufio"
	"fmt"
	"github.com/davidlukac/advent-of-code/2022/library"
	"strconv"
	"strings"
)

type SectionID int

type Assignment struct {
	Start SectionID
	End   SectionID
}

type Pair struct {
	AsgElfOne             *Assignment
	AsgElfTwo             *Assignment
	OneFullyContainsOther bool
	Overlap               bool
}

func main() {
	inputFile, closeFn := library.OpenFileFromArgs()
	defer closeFn()

	var pairs []*Pair
	containEachOtherCount := 0
	overlapCount := 0

	scanner := bufio.NewScanner(inputFile)
	for scanner.Scan() {
		pair, err := d3ParseLine(scanner.Text())
		if err != nil {
			panic(err)
		}
		if pair.CheckIfOneFullyContainsOther() {
			containEachOtherCount++
		}
		if pair.IsOverlap() {
			overlapCount++
		}
		pairs = append(pairs, pair)
	}

	println(containEachOtherCount)
	println(overlapCount)
}

func (p *Pair) IsOverlap() bool {
	p.Overlap = false
	firstAsg, secondAsg := OrderAssignmentPair(p.AsgElfOne, p.AsgElfTwo)

	if secondAsg.Start <= firstAsg.End {
		p.Overlap = true
	}

	return p.Overlap
}

func OrderAssignmentPair(a *Assignment, b *Assignment) (*Assignment, *Assignment) {
	if a.Start <= b.Start {
		return a, b
	}

	return b, a
}

func (p *Pair) CheckIfOneFullyContainsOther() bool {
	p.OneFullyContainsOther = false

	if p.AsgElfOne.FullyContains(p.AsgElfTwo) || p.AsgElfTwo.FullyContains(p.AsgElfOne) {
		p.OneFullyContainsOther = true
	}

	return p.OneFullyContainsOther
}

func (a *Assignment) FullyContains(b *Assignment) bool {
	if b.Start >= a.Start && b.End <= a.End {
		return true
	}

	return false
}

func d3ParseLine(l string) (*Pair, error) {
	parts := strings.Split(l, ",")
	if len(parts) != 2 {
		return nil, fmt.Errorf("invalid input - two assignments separated by comma are expected")
	}
	asgOne, err := parseAssignment(parts[0])
	if err != nil {
		return nil, err
	}
	asgTwo, err := parseAssignment(parts[1])
	if err != nil {
		return nil, err
	}

	return &Pair{AsgElfOne: asgOne, AsgElfTwo: asgTwo}, nil
}

func parseAssignment(a string) (*Assignment, error) {
	formatError := fmt.Errorf("invalid input '%s' - two section IDs separated by dash are expected", a)

	parts := strings.Split(a, "-")

	startInt, err := strconv.Atoi(strings.TrimSpace(parts[0]))
	if err != nil {
		return nil, formatError
	}

	endInt, err := strconv.Atoi(strings.TrimSpace(parts[1]))
	if err != nil {
		return nil, formatError
	}

	return &Assignment{SectionID(startInt), SectionID(endInt)}, nil
}
