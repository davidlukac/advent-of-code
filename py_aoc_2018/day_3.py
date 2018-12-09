from __future__ import annotations

import time
from typing import Dict, TextIO, Tuple

from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str


class Claim:
    def __init__(self, cid: int, x: int, y: int, size_x: int, size_y: int) -> None:
        self.cid = cid
        self.x = x
        self.y = y
        self.size_x = size_x
        self.size_y = size_y
        self.x_max = x + size_x - 1
        self.y_max = y + size_y - 1

    def is_on(self, x: int, y: int) -> bool:
        if self.x <= x <= self.x_max and self.y <= y <= self.y_max:
            return True
        else:
            return False

    def __eq__(self, o: object) -> bool:
        if isinstance(o, Claim):
            return self.__dict__ == o.__dict__
        else:
            return False

    @staticmethod
    def from_string(s: str) -> Claim:
        # #1316 @ 818,356: 13x14
        parts = s.strip().split(' ')
        cid = int(parts[0][1:])
        x, y = tuple(int(p) for p in parts[2][:-1].strip().split(','))
        size_x, size_y = tuple(int(p) for p in parts[3].strip().split('x'))

        return Claim(cid, x, y, size_x, size_y)


def load_claims(f: TextIO) -> Tuple[int, int, Dict[int, Claim]]:
    size_x = 0
    size_y = 0
    claims = dict()

    for l in stream_lines_as_str(f):
        c = Claim.from_string(l)
        if size_x < c.x_max:
            size_x = c.x_max
        if size_y < c.y_max:
            size_y = c.y_max
        claims[c.cid] = c

    # Since we're counting the MAX coordinates from zero, we need to +1 when calculating the size of the canvas.
    return size_x + 1, size_y + 1, claims


def count_too_occupied(size_x: int, size_y: int, claims: Dict[int, Claim], print_throttle: float = 5.0) -> int:
    too_occupied = 0
    last_print = time.time()
    throughput = 0.0

    for y in range(size_y):
        t_row_start = time.time()

        for x in range(size_x):
            xy_occupations = 0
            for cid in list(claims.keys()):
                if claims[cid].is_on(x, y):
                    xy_occupations += 1

                t = time.time()
                if t - last_print >= print_throttle:
                    print(f'Currently on {x}x{y} and found {too_occupied} over-claimed sq. inches. '
                          f'{len(claims)} claims remain in the list. '
                          f'Throughput is {throughput:.2f} sq.inch/s.')
                    last_print = t

                if y > claims[cid].y_max:
                    claims.pop(cid)

                if xy_occupations == 2:
                    too_occupied += 1
                    break

        t_row_finish = time.time()
        throughput = size_x / (t_row_finish - t_row_start)

    return too_occupied


def day_3() -> int:
    with open(get_input_file_path(3), 'r') as f:
        size_x, size_y, claims = load_claims(f)
    print(f'Loaded {len(claims)} claims; the canvas has size {size_x}x{size_y}.')

    too_occupied = count_too_occupied(size_x, size_y, claims)

    return too_occupied


if __name__ == '__main__':
    res_too_occupied = day_3()
    print(f"There are {res_too_occupied} sq. inches with 2+ claims.")
