import io
import unittest

import pytest

from py_aoc_2018.day_1 import day_1
from py_aoc_2018.day_2 import day_2, find_matching
from py_aoc_2018.day_3 import Claim, load_claims


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


class TestDay3(unittest.TestCase):
    def test_claim_factory(self):
        assert Claim(1, 2, 3, 4, 5) == Claim.from_string('#1 @ 2,3: 4x5')
        assert Claim(2, 2, 3, 4, 5) != Claim.from_string('#1 @ 2,3: 4x5')

    def test_canvas_size(self):
        data = [
            '#1 @ 1,3: 4x4',
            '#2 @ 3,1: 4x4',
            '#3 @ 5,5: 2x2'
        ]

        stream = io.StringIO('\n'.join(data))
        assert (7, 7) == load_claims(stream)[:-1]


if __name__ == '__main__':
    pytest.main()
