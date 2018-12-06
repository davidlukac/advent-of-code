from __future__ import annotations

from typing import List, TextIO, Tuple

from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str


class Claim:
    def __init__(self, cid: int, x: int, y: int, size_x: int, size_y: int) -> None:
        self.cid = cid
        self.x = x
        self.y = y
        self.size_x = size_x
        self.size_y = size_y
        self.x_max = x + size_x
        self.y_max = y + size_y

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


def load_claims(f: TextIO) -> Tuple[int, int, List[Claim]]:
    size_x = 0
    size_y = 0
    claims = list()

    for l in stream_lines_as_str(f):
        c = Claim.from_string(l)
        if size_x < c.x_max:
            size_x = c.x_max
        if size_y < c.y_max:
            size_y = c.y_max
        claims.append(c)

    return size_x, size_y, claims


def day_3():
    size_x = 0
    size_y = 0
    claims = list()

    with open(get_input_file_path(3), 'r') as f:
        size_x, size_y, claims = load_claims(f)
