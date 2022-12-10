package main

import (
	"bufio"
	"errors"
	"fmt"
	"github.com/davidlukac/advent-of-code/2022/library"
	"io"
)

type CommBuffer struct {
	buffer []*rune
}

const (
	PacketBufferLength  int = 4
	MessageBufferLength int = 14
)

func main() {
	inputFile, closeFn := library.OpenFileFromArgs()
	defer closeFn()

	counter := 0
	startOfPacket := 0
	startOfMessage := 0
	packetBuffer := NewCommBuffer(PacketBufferLength)
	msgBuffer := NewCommBuffer(MessageBufferLength)

	reader := bufio.NewReader(inputFile)
	for {
		r, _, err := reader.ReadRune()
		if err != nil {
			if errors.Is(err, io.EOF) {
				break
			}
			panic(err)
		}
		counter++
		packetBuffer.Push(r)
		if startOfPacket == 0 {
			if packetBuffer.IsUnique() && packetBuffer.IsFull() {
				startOfPacket = counter
			}
		}
		msgBuffer.Push(r)
		if startOfMessage == 0 {
			if msgBuffer.IsUnique() && msgBuffer.IsFull() {
				startOfMessage = counter
				break
			}
		}
	}

	fmt.Printf("start of packet: %d\n", startOfPacket)
	fmt.Printf("start of message: %d\n", startOfMessage)
}

func (b *CommBuffer) IsFull() bool {
	isFull := false

	if len(b.buffer) == b.Length() {
		isFull = true
	}

	return isFull
}

// Length counts number of runes in the buffer ignoring nil values.
func (b *CommBuffer) Length() int {
	l := 0

	for _, r := range b.buffer {
		if r != nil {
			l++
		}
	}

	return l
}

// IsUnique checks if runes in the buffer are non-repeating (each is unique). Nil values are ignored.
func (b *CommBuffer) IsUnique() bool {
	m := map[rune]int{}

	for _, r := range b.buffer {
		if r == nil {
			continue
		}
		_, exists := m[*r]
		if exists {
			return false
		} else {
			m[*r] = 1
		}
	}

	return true
}

func (b *CommBuffer) Push(r rune) *CommBuffer {
	b.buffer = append(b.buffer[1:len(b.buffer)], &r)
	return b
}

func NewCommBuffer(l int) *CommBuffer {
	buffer := make([]*rune, l)
	return &CommBuffer{buffer: buffer}
}
