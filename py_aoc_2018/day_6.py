from __future__ import annotations

from typing import Generator, List, Tuple, Dict, Union

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


def calculate_distance(p1: Point, p2: Point) -> int:
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


class Cell:
    def __init__(self, x: int, y: int, belongs_to: Union[Point, None]) -> None:
        super().__init__()
        self.x = x
        self.y = y
    @property
    def distance(self):
        return self.__distance


class Plane:
    def __init__(self) -> None:
        super().__init__()
        self.plane = dict()  # type: Dict[Tuple[int, int], Cell]

    def get(self, x: int, y: int) -> Cell:
        return self.plane.get((x, y))

    def set(self, c: Cell) -> Plane:
        assert isinstance(c.x, int)
        assert isinstance(c.y, int)
        self.plane[(c.x, c.y)] = c

        return self


def find_distances(corner1: Point, corner2: Point, points: List[Point]):
    plane = Plane()

    for x in range(corner1.x, corner2.x):
        for y in range(corner1.y, corner2.y):

            cell = plane.get(x, y)
            if cell is None:
                cell = Cell(x, y, None)
                plane.set(cell)

            for p in points:
                point_distance = calculate_distance(Point(x, y), p)
                if cell.belongs_to:
                    if point_distance < cell.distance:
                else:
                    cell.belongs_to = p


def main():
    with open(get_input_file_path(6), 'r') as f:
        points = read_points(stream_lines_as_str(f))

    c1, c2 = find_corners(points)
    find_distances(c1, c2, points)


if __name__ == '__main__':
    main()
