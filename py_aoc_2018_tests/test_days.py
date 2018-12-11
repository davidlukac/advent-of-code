import io
import unittest
from collections import OrderedDict

import pytest

from py_aoc_2018.day_1 import day_1
from py_aoc_2018.day_2 import day_2, find_matching
from py_aoc_2018.day_3 import Claim, count_too_occupied, load_claims, optimize_claims


class TestDay1(unittest.TestCase):
    def test(self):
        final_frequency, matching_frequency, _ = day_1()
        assert 578 == final_frequency
        assert 82516 == matching_frequency


class TestDay2(unittest.TestCase):
    def test(self):
        assert 8820 == day_2()[0]

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

        data = [
            '#1 @ 1,1: 3x2',
            '#2 @ 2,1: 2x4',
            '#3 @ 3,2: 5x2',
            '#4 @ 5,3: 3x5'
        ]

        stream = io.StringIO('\n'.join(data))
        assert (8, 8) == load_claims(stream)[:-1]

    def test_claim_is_on(self):
        c = Claim(1, 1, 1, 3, 2)
        assert not c.is_on(0, 0)
        assert not c.is_on(2, 0)
        assert not c.is_on(4, 2)
        assert not c.is_on(0, 2)
        assert not c.is_on(2, 3)
        assert not c.is_on(4, 4)

        assert c.is_on(1, 1)
        assert c.is_on(3, 2)

        c = Claim.from_string('#3 @ 3,2: 5x2')
        assert c.is_on(3, 2)
        assert c.is_on(7, 2)
        assert c.is_on(3, 3)
        assert c.is_on(7, 3)
        assert c.is_on(5, 3)

    def test_count_too_occupied(self):
        data = [
            '#1 @ 1,3: 4x4',
            '#2 @ 3,1: 4x4',
            '#3 @ 5,5: 2x2'
        ]

        stream = io.StringIO('\n'.join(data))
        size_x, size_y, claims = load_claims(stream)

        assert 4 == count_too_occupied(size_x, size_y, claims)

        data = [
            '#1 @ 1,1: 3x2',
            '#2 @ 2,1: 2x4',
            '#3 @ 3,2: 5x2',
            '#4 @ 5,3: 3x5'
        ]

        stream = io.StringIO('\n'.join(data))
        size_x, size_y, claims = load_claims(stream)

        assert count_too_occupied(size_x, size_y, claims) == 8

    def test_sort(self):
        data = [
            '#4 @ 3,2: 5x5',
            '#2 @ 2,1: 2x4',
            '#5 @ 5,3: 3x5',
            '#3 @ 3,2: 5x2',
            '#1 @ 1,1: 3x2',
        ]

        stream = io.StringIO('\n'.join(data))
        _, _, claims = load_claims(stream)

        claim_ordered = optimize_claims(claims)

        claims_ordered_expected = OrderedDict({
            1: Claim.from_string('#1 @ 1,1: 3x2'),
            2: Claim.from_string('#2 @ 2,1: 2x4'),
            3: Claim.from_string('#3 @ 3,2: 5x2'),
            4: Claim.from_string('#4 @ 3,2: 5x5'),
            5: Claim.from_string('#5 @ 5,3: 3x5'),
        })

        for c_actual, c_expected in zip(claim_ordered.values(), claims_ordered_expected.values()):
            assert c_actual == c_expected


if __name__ == '__main__':
    pytest.main()
