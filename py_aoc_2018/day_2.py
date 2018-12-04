from collections import Counter

from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str


def day_2():
    twos = 0
    threes = 0

    with open(get_input_file_path(2), 'r') as f:
        for line in stream_lines_as_str(f):
            c = Counter(Counter(line).values())
            if c.get(2):
                twos += 1
            if c.get(3):
                threes += 1

    return twos * threes


def main():
    checksum = day_2()
    print(f"Checksum of the boxes is {checksum}.")


if __name__ == '__main__':
    main()
