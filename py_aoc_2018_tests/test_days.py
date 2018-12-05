import unittest

import pytest

from py_aoc_2018.day_1 import day_1
from py_aoc_2018.day_2 import day_2, find_matching


class TestDay1(unittest.TestCase):
    def test(self):
        final_frequency, matching_frequency, _ = day_1()
        assert 578 == final_frequency
        assert 82516 == matching_frequency


class TestDay2(unittest.TestCase):
    def test(self):
        assert 8820 == day_2()

    def test_matching(self):
        data = [
            'abcde',
            'fghij',
            'klmno',
            'pqrst',
            'fguij',
            'axcye',
            'wvxyz'
        ]

        cache = {}
        res = []

        for d in data:
            find_matching(d, cache, res)

        assert ['fgij'] == res


if __name__ == '__main__':
    pytest.main()
