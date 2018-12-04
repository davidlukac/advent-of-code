import unittest

import pytest

from py_aoc_2018.day_1 import day_1


class TestDay1(unittest.TestCase):
    def test(self):
        final_frequency, matching_frequency, _ = day_1()
        assert 578 == final_frequency
        assert 82516 == matching_frequency


if __name__ == '__main__':
    pytest.main()
