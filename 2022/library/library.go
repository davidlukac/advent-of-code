package library

import (
	"errors"
	"fmt"
	"os"
)

func OpenFileFromArgs() (*os.File, func()) {
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
	closeFn := func() {
		err := inputFile.Close()
		if err != nil {
			panic("failed to close input file")
		}
	}

	return inputFile, closeFn
}
