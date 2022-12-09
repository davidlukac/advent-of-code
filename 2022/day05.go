package main

import (
	"bufio"
	"fmt"
	"github.com/davidlukac/advent-of-code/2022/library"
	"io"
	"regexp"
	"sort"
	"strconv"
	"strings"
)

type Container rune

type Stack []*Container

type Storage map[int]Stack

type Move struct {
	Count       int
	Source      int
	Destination int
}

func main() {
	inputFile, closeFn := library.OpenFileFromArgs()
	defer closeFn()

	readingStorage := true

	storage := Storage{}

	scanner := bufio.NewScanner(inputFile)
	for scanner.Scan() {
		line := scanner.Text()
		line = strings.TrimSpace(line)

		if readingStorage {
			if len(line) == 0 {
				// This was the separator line between storage and instructions.
				readingStorage = false
				continue
			}

			hasNumbers, err := regexp.MatchString("\\d", line)
			if err != nil {
				panic("invalid regular expressions")
			}
			if hasNumbers {
				// This is the last storage line, we can skip it.
				continue
			}

			storage.ParseStorageInput(line)
		} else {
			move, err := storage.ParseInstruction(line)
			if err != nil {
				panic(err)
			}
			_, err = storage.Move9000(*move)
			if err != nil {
				panic(err)
			}
		}
	}

	topContainers := storage.GetTopContainers()
	for _, c := range *topContainers {
		print(string(*c))
	}
	println()

	// --- Part #2 -----------------------------------------------------------------------------------------------------

	readingStorage = true

	storage = Storage{}

	_, err := inputFile.Seek(0, io.SeekStart)
	if err != nil {
		panic(err)
	}
	scanner = bufio.NewScanner(inputFile)
	for scanner.Scan() {
		line := scanner.Text()
		line = strings.TrimSpace(line)

		if readingStorage {
			if len(line) == 0 {
				// This was the separator line between storage and instructions.
				readingStorage = false
				continue
			}

			hasNumbers, err := regexp.MatchString("\\d", line)
			if err != nil {
				panic("invalid regular expressions")
			}
			if hasNumbers {
				// This is the last storage line, we can skip it.
				continue
			}

			storage.ParseStorageInput(line)
		} else {
			move, err := storage.ParseInstruction(line)
			if err != nil {
				panic(err)
			}
			_, err = storage.Move9001(*move)
			if err != nil {
				panic(err)
			}
		}
	}

	topContainers = storage.GetTopContainers()
	for _, c := range *topContainers {
		print(string(*c))
	}
	println()
}

func (s *Storage) GetTopContainers() *Stack {
	keys := make([]int, len(*s))
	i := 0
	for k := range *s {
		keys[i] = k
		i++
	}
	sort.Ints(keys)
	stack := make(Stack, len(*s))
	i = 0
	for _, k := range keys {
		sourceStack := (*s)[k]
		stack[i] = sourceStack[len(sourceStack)-1]
		i++
	}
	return &stack
}

func (s *Storage) Move9000(m Move) (*Stack, error) {
	stack := Stack{}
	for i := 0; i < m.Count; i++ {
		container, err := s.MoveSingleContainer(m.Source, m.Destination)
		stack = append(stack, container)
		if err != nil {
			return &stack, err
		}
	}

	return &stack, nil
}

func (s *Storage) Move9001(m Move) (*Stack, error) {
	errMsg := fmt.Errorf("stack with index %d doesn't exist - can not perform the move", m.Source)
	stack := Stack{}

	fromStack, exists := (*s)[m.Source]
	if !exists {
		return nil, errMsg
	}
	if len(fromStack) < m.Count {
		return nil, fmt.Errorf("not enough containers in the source stack - "+
			"can not perform the move of %d containers", m.Count)
	}
	toStack, exists := (*s)[m.Destination]
	if !exists {
		return nil, errMsg
	}

	fromStackLen := len(fromStack)
	containers := fromStack[fromStackLen-m.Count : fromStackLen]
	fromStack = fromStack[:len(fromStack)-m.Count]
	toStack = append(toStack, containers...)
	(*s)[m.Source] = fromStack
	(*s)[m.Destination] = toStack

	return &stack, nil
}

func (s *Storage) MoveSingleContainer(from, to int) (*Container, error) {
	errMsg := fmt.Errorf("stack with index %d doesn't exist - can not perform the move", from)
	fromStack, exists := (*s)[from]
	if !exists {
		return nil, errMsg
	}
	if len(fromStack) < 1 {
		return nil, fmt.Errorf("not enough containers in the source stack - can not perform the move")
	}
	toStack, exists := (*s)[to]
	if !exists {
		return nil, errMsg
	}

	container := fromStack[len(fromStack)-1]
	fromStack = fromStack[:len(fromStack)-1]
	toStack = append(toStack, container)
	(*s)[from] = fromStack
	(*s)[to] = toStack

	return container, nil
}

func (s *Storage) ParseStorageInput(l string) {
	// Container letters are starting at index 1 and then are placed on +4 positions.
	for i, si := 1, 1; i < len(l); i, si = i+4, si+1 {
		containerRune := l[i]
		if containerRune == ' ' {
			continue
		}
		container := Container(containerRune)
		stack, exists := (*s)[si]
		if exists {
			// Prepend the container to stack, because we're reading from the top.
			stack = append([]*Container{&container}, stack...)
			(*s)[si] = stack
		} else {
			(*s)[si] = Stack{&container}
		}
	}
}

func (s *Storage) ParseInstruction(l string) (*Move, error) {
	errMsg := fmt.Errorf("invalid input - instructions are expeted in format: 'move N from X to Y'")

	l = strings.TrimSpace(l)
	parts := strings.Split(l, " ")
	if len(parts) != 6 {
		return nil, errMsg
	}

	count, err := strconv.Atoi(parts[1])
	if err != nil {
		return nil, fmt.Errorf("invalid input - instructions are expeted in format: 'move N from X to Y'")
	}

	source, err := strconv.Atoi(parts[3])
	if err != nil {
		return nil, fmt.Errorf("invalid input - instructions are expeted in format: 'move N from X to Y'")
	}

	destination, err := strconv.Atoi(parts[5])
	if err != nil {
		return nil, fmt.Errorf("invalid input - instructions are expeted in format: 'move N from X to Y'")
	}

	return &Move{Count: count, Source: source, Destination: destination}, nil
}
