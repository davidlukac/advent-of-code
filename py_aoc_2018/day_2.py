from collections import Counter
from typing import Dict, List

from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str


def find_matching(line: str, cache: Dict[str, None], matching_boxes: List[str]):
    for k in cache.keys():
        res = ''
        differences = 0
        for c1, c2 in zip(line, k):
            if differences <= 1:
                if c1 == c2:
                    res += c1
                else:
                    differences += 1
            else:
                break
        if differences == 1:
            matching_boxes.append(res)

    cache[line] = None


def day_2():
    twos = 0
    threes = 0

    # This could be a size-limited cache in which we are comparing, should the input stream of data be endless.
    cache = dict()
    matching_boxes = list()

    with open(get_input_file_path(2), 'r') as f:
        for line in stream_lines_as_str(f):
            line = line.strip()
            c = Counter(Counter(line).values())
            if c.get(2):
                twos += 1
            if c.get(3):
                threes += 1

            find_matching(line, cache, matching_boxes)

    return twos * threes, matching_boxes


def main():
    checksum, matching_boxes = day_2()
    print(f"Checksum of the boxes is {checksum}.")
    print(f"Matching boxes are {matching_boxes}")


if __name__ == '__main__':
    main()
