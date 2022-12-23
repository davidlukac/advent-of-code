package main

import (
	"bufio"
	"fmt"
	"github.com/davidlukac/advent-of-code/2022/library"
	"math"
	"reflect"
	"regexp"
	"sort"
	"strconv"
	"strings"
	"time"
)

const (
	DiskSize    = 70000000
	SpaceNeeded = 30000000
)

type ElfCmd struct {
	Command string
	Args    []string
}

type LsCmd struct {
	ElfCmd
}

type CdCmd struct {
	ElfCmd
}

// --- FileSystem Model ------------------------------------------------------------------------------------------------

type FsNode interface {
	Name() string
	Parent() *DirFs
	SetParent(*DirFs)
	Size() int
}

type DirFs struct {
	name   string
	parent *DirFs
	size   int
	files  []*FileFs
	dirs   []*DirFs
}

type FileFs struct {
	name   string
	parent *DirFs
	size   int
}

type ElfFilesystem struct {
	Root DirFs
	Cwd  *DirFs
}

func main() {
	inputFile, closeFn := library.OpenFileFromArgs()
	defer closeFn()

	root := DirFs{
		name:   "/",
		parent: nil,
		files:  make([]*FileFs, 0),
		dirs:   make([]*DirFs, 0),
	}

	fs := ElfFilesystem{Root: root, Cwd: &root}

	scanner := bufio.NewScanner(inputFile)
	for scanner.Scan() {
		line := scanner.Text()
		parsedLine, err := parseDay7Line(line)
		if err != nil {
			panic(err)
		}
		switch t := reflect.TypeOf(parsedLine); {
		case t == reflect.TypeOf(&CdCmd{}):
			var cdCmd *CdCmd
			cdCmd = parsedLine.(*CdCmd)
			_, err = fs.ChangeDir(cdCmd)
			if err != nil {
				panic(err)
			}
		case t == reflect.TypeOf(&LsCmd{}):
		case t == reflect.TypeOf(&DirFs{}):
			fs.AddDirToCwd(parsedLine.(*DirFs))
		case t == reflect.TypeOf(&FileFs{}):
			fs.AddFileToCwd(parsedLine.(*FileFs))
		}
	}

	sum, _ := fs.FilterAndCalculateTotalSize(0, 100000)
	println(sum)

	dirToDelete := fs.FindSmallestDirToDelete(SpaceNeeded - (DiskSize - fs.Root.size))
	println(dirToDelete.size)
}

func (efs *ElfFilesystem) FindSmallestDirToDelete(neededSpace int) *DirFs {
	_, dirs := efs.FilterAndCalculateTotalSize(0, math.MaxInt)

	sort.Slice(dirs, func(i, j int) bool {
		return dirs[i].size < dirs[j].size
	})

	for _, d := range dirs {
		if d.size >= neededSpace {
			return d
		}
	}

	return nil
}

func (efs *ElfFilesystem) GetMapOfDirsBySize() map[*DirFs]int {
	m := map[*DirFs]int{}

	return m
}

// FilterAndCalculateTotalSize traverses all directories recursively, calculates their size, filters out directories
// that are about of bounds of min and max (including) and returns total sum of the sizes and list of directories that
// matched min & max criteria.
func (efs *ElfFilesystem) FilterAndCalculateTotalSize(min, max int) (int, []*DirFs) {
	ch := make(chan *DirFs)

	sum := 0
	var dirs []*DirFs

	go func() {
		for d := range ch {
			sum += d.size
			dirs = append(dirs, d)
		}
	}()

	efs.Root.Size(ch, min, max)

	// Figure out how to avoid explicit waiting.
	time.Sleep(3 * time.Second)
	close(ch)

	return sum, dirs
}

func (d *DirFs) Size(ch chan *DirFs, min, max int) int {
	subFolders := 0

	for _, dir := range d.Dirs() {
		subFolders += dir.Size(ch, min, max)
	}

	d.size = subFolders + d.SumFileSize()
	if d.size >= min && d.size <= max {
		ch <- d
	}

	return d.size
}

func (d *DirFs) FilesAndDirs() ([]*FileFs, []*DirFs) {
	return d.Files(), d.Dirs()
}

func (d *DirFs) Files() []*FileFs {
	return d.files
}

func (d *DirFs) Dirs() []*DirFs {
	return d.dirs
}

func (d *DirFs) SumFileSize() int {
	sum := 0

	for _, file := range d.Files() {
		sum += file.Size()
	}

	return sum
}

func (f *FileFs) Size() int {
	return f.size
}

func (d *DirFs) SetParent(p *DirFs) {
	d.parent = p
}

func (f *FileFs) SetParent(p *DirFs) {
	f.parent = p
}

func (efs *ElfFilesystem) AddFileToCwd(f *FileFs) {
	cwd := efs.Cwd
	f.SetParent(cwd)
	cwd.files = append(cwd.files, f)
	efs.Cwd = cwd
}

func (efs *ElfFilesystem) AddDirToCwd(d *DirFs) {
	cwd := efs.Cwd
	d.SetParent(cwd)
	cwd.dirs = append(cwd.dirs, d)
	efs.Cwd = cwd
}

func (efs *ElfFilesystem) ChangeDir(c *CdCmd) (*ElfFilesystem, error) {
	if len(c.Args) < 1 {
		return efs, fmt.Errorf("invalid cd command - not taget dir present")
	}

	targetDirName := c.Args[0]
	// Handle special cases.
	switch targetDirName {
	case "/":
		efs.Cwd = &efs.Root
		return efs, nil
	case "..":
		efs.Cwd = efs.Cwd.Parent()
		return efs, nil
	}

	for _, dir := range efs.Cwd.Dirs() {
		if dir.Name() == targetDirName {
			efs.Cwd = dir
			return efs, nil
		}
	}

	return efs, fmt.Errorf("invalid cd command - target directory '%s' doesn't exist", targetDirName)
}

func (f *FileFs) Name() string {
	return f.name
}

func (f *FileFs) Parent() *DirFs {
	return f.parent
}

func (d *DirFs) Name() string {
	return d.name
}

func (d *DirFs) Parent() *DirFs {
	return d.parent
}

func parseDay7Line(l string) (interface{}, error) {
	l = strings.TrimSpace(l)
	if len(l) < 3 {
		return nil, fmt.Errorf("invalid line '%s' - too short", l)
	}

	startsWithDigit := regexp.MustCompile("\\d")
	startsWithLetter := regexp.MustCompile("[a-zA-Z]")

	switch first := l[0]; {
	case first == '$':
		return ElfCmdFactory(l)
	case startsWithDigit.MatchString(string(first)):
		return FileFactory(l)
	case startsWithLetter.MatchString(string(first)):
		return DirFactory(l)
	default:
		return nil, fmt.Errorf("invalid line '%s' - couldn't identify type of line to parse", l)
	}
}

func ElfCmdFactory(l string) (interface{}, error) {
	l = strings.TrimSpace(l)
	invalidFormatError := fmt.Errorf("invalid format - command is expected in format of '$ CMD ARGS...'; " +
		"supported commands are: ['cmd', 'ls']")

	parts := strings.Split(l, " ")
	if len(parts) < 2 {
		return nil, invalidFormatError
	}

	switch cmd := parts[1]; {
	case cmd == "cd":
		return &CdCmd{ElfCmd{Command: cmd, Args: parts[2:]}}, nil
	case cmd == "ls":
		return &LsCmd{ElfCmd{Command: cmd, Args: parts[2:]}}, nil
	}

	return nil, invalidFormatError
}

func FileFactory(l string) (*FileFs, error) {
	l = strings.TrimSpace(l)
	parts := strings.Split(l, " ")
	invalidFormatError := fmt.Errorf("invalid file format - expected: 'INT_SIZE FILENAME', got %s", l)

	if len(parts) != 2 {
		return nil, invalidFormatError
	}

	size, err := strconv.Atoi(parts[0])
	if err != nil {
		return nil, invalidFormatError
	}

	return &FileFs{
		name: parts[1],
		size: size,
	}, nil
}

func DirFactory(l string) (*DirFs, error) {
	l = strings.TrimSpace(l)
	parts := strings.Split(l, " ")
	if len(parts) != 2 || parts[0] != "dir" {
		return nil, fmt.Errorf("invalid dir format - expected: 'dir DIRNAME', got: %s", l)
	}

	return &DirFs{
		name:  parts[1],
		dirs:  make([]*DirFs, 0),
		files: make([]*FileFs, 0),
	}, nil
}

func (c *ElfCmd) String() string {
	return fmt.Sprintf("command: %s; args: %v", c.Command, c.Args)
}

func (d *DirFs) String() string {
	return fmt.Sprintf("dir: %s", d.Name())
}

func (f *FileFs) String() string {
	return fmt.Sprintf("file: %s of size %d", f.Name(), f.Size)
}
