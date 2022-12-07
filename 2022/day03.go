package main

import (
	"bufio"
	"errors"
	"fmt"
	"io"
	"os"
	"strings"
	"unicode"
)

type ItemType rune
type Rucksack string
type Group struct {
	Badge   ItemType
	Members []Rucksack
}
type Gang []Group

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

	var commonItemTypes []ItemType
	prioritiesSum := 0

	scanner := bufio.NewScanner(inputFile)
	for scanner.Scan() {
		rucksack := Rucksack(scanner.Text())
		itemType, err := findCommonItemType(rucksack)
		if err != nil {
			panic(err)
		}
		commonItemTypes = append(commonItemTypes, itemType)
		priority, err := itemType.GetPriority()
		if err != nil {
			panic(err)
		}
		prioritiesSum += priority
	}

	println(prioritiesSum)

	_, err = inputFile.Seek(0, io.SeekStart)
	if err != nil {
		panic(err)
	}

	var gang Gang
	var group Group
	prioritiesSum = 0

	scanner = bufio.NewScanner(inputFile)
	for scanner.Scan() {
		rucksack := Rucksack(scanner.Text())
		gang, group = gang.AddRucksack(rucksack)
		if len(group.Members) == 3 {
			if err = group.FindBadge(); err != nil {
				panic(err)
			}
			priority, err := group.Badge.GetPriority()
			if err != nil {
				panic(err)
			}
			prioritiesSum += priority
		}
	}

	println(prioritiesSum)
}

func (g *Group) FindBadge() error {
	if len((*g).Members) != 3 {
		return fmt.Errorf("to find group badge, all three members have to be set")
	}

	for _, r := range string(g.Members[0]) {
		if strings.ContainsRune(string(g.Members[1]), r) && strings.ContainsRune(string(g.Members[2]), r) {
			it, err := NewItemType(r)
			if err != nil {
				return err
			}
			g.Badge = it
			return nil
		}
	}

	return nil
}

func (g *Gang) AddRucksack(r Rucksack) (Gang, Group) {
	if len(*g) > 0 {
		lastGroup := (*g)[len(*g)-1]
		if len(lastGroup.Members) < 3 {
			lastGroup.Members = append(lastGroup.Members, r)
			(*g)[len(*g)-1] = lastGroup
			return *g, lastGroup
		}
	}

	newGroup := Group{Members: []Rucksack{r}}
	newGang := append(*g, newGroup)
	return newGang, newGroup
}

func NewItemType(r rune) (ItemType, error) {
	if (r >= 'a' && r <= 'z') || (r >= 'A' && r <= 'Z') {
		return ItemType(r), nil
	}

	return ItemType(' '), fmt.Errorf("invalid input - item type must be a-zA-Z")
}

func findCommonItemType(r Rucksack) (ItemType, error) {
	c1 := r[0 : len(r)/2]
	c2 := r[len(r)/2:]
	for _, r := range c1 {
		if strings.ContainsRune(string(c2), r) {
			return NewItemType(r)
		}
	}
	return ItemType(' '),
		fmt.Errorf("invalid input - both compartments must include exactly one item of the same type")
}

func (i ItemType) GetPriority() (int, error) {
	if !((i >= 'a' && i <= 'z') || (i >= 'A' && i <= 'Z')) {
		return 0, fmt.Errorf("invalid ItemType - item type must be a-zA-Z")
	}

	if unicode.IsLower(rune(i)) {
		return int(rune(i) - 'a' + 1), nil
	} else {
		return int(rune(i) - 'A' + 27), nil
	}
}
