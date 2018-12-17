import io
import unittest

import pytest

from py_aoc_2018.commons import stream_lines_as_str
from py_aoc_2018.day_6 import read_points, Point, calculate_difference, find_corners


class TestPoint(unittest.TestCase):
    def test_construct(self):
        assert Point.from_string('1,1') == Point(1, 1)

    def test_difference(self):
        assert calculate_difference(Point(1, 1), Point(2, 1)) == 1
        assert calculate_difference(Point(1, 1), Point(2, 2)) == 2

    def test_corners(self):
        assert find_corners([
            Point(10, -5),
            Point(13, -20),
            Point(2, 3),
            Point(15, -24)
        ]) == (Point(2, -24), Point(15, 3))


class TestDay6(unittest.TestCase):
    def test(self):
        data = """
1, 1
1, 6
8, 3
3, 4
5, 5
8, 9
"""
        stream = io.StringIO(data)
        points = read_points(stream_lines_as_str(stream))

        assert points == [Point(1, 1), Point(1, 6), Point(8, 3), Point(3, 4), Point(5, 5), Point(8, 9)]


if __name__ == '__main__':
    pytest.main()
