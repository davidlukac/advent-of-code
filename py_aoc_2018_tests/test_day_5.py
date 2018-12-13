import unittest

import pytest

from py_aoc_2018.day_5 import is_opposite, reduce


class TestDay5(unittest.TestCase):
    def test_is_opposite(self):
        assert is_opposite('a', 'A')
        assert is_opposite('B', 'b')

        assert not is_opposite('a', 'AA')
        assert not is_opposite('BB', 'b')
        assert not is_opposite('BB', 'BB')
        assert not is_opposite('cc', 'cc')

    def test_reduce(self):
        assert reduce('aA') == ''
        assert reduce('abBA') == ''
        assert reduce('abAB') == 'abAB'
        assert reduce('aabAAB') == 'aabAAB'
        assert reduce('dabAcCaCBAcCcaDA') == 'dabCBAcaDA'
        assert reduce('abcdDCBA') == ''
        assert reduce('abcdDCBAx') == 'x'
        assert reduce('xabcdDCBA') == 'x'


if __name__ == '__main__':
    pytest.main()
