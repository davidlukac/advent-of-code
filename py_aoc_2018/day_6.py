from __future__ import annotations

from typing import Generator, List, Tuple

from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str


class Point:
    def __init__(self, x: int, y: int) -> None:
        super().__init__()
        self.x = x
        self.y = y

    @staticmethod
    def from_string(s: str) -> Point:
        x, y = s.strip().replace(' ', '').split(',')

        return Point(int(x), int(y))

    def __eq__(self, o: object) -> bool:
        if isinstance(o, Point):
            return o.__dict__ == self.__dict__

        return False

    def __repr__(self) -> str:
        return f'{self.x} x {self.y}'


def calculate_difference(p1: Point, p2: Point) -> int:
    return abs(p1.x - p2.x) + abs(p1.y - p2.y)


def find_corners(points: List[Point]) -> Tuple[Point, Point]:
    assert len(points) > 0

    first_point = points.pop()
    x, y, x_max, y_max = first_point.x, first_point.y, first_point.x, first_point.y

    for p in points:
        if p.x < x:
            x = p.x
        if p.y < y:
            y = p.y
        if p.x > x_max:
            x_max = p.x
        if p.y > y_max:
            y_max = p.y

    return Point(x, y), Point(x_max, y_max)


def read_points(lines: Generator[str]) -> List[Point]:
    points = list()
    for l in lines:
        if l.strip():
            points.append(Point.from_string(l))

    return points


def main():
    with open(get_input_file_path(6), 'r') as f:
        points = stream_lines_as_str(f)


if __name__ == '__main__':
    main()
