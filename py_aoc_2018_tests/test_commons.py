import unittest
import os
import pytest
import py_aoc_2018
from py_aoc_2018.commons import get_input_file_path


class TestInputFilePath(unittest.TestCase):
    def test(self):
        assert os.path.join(
            os.path.dirname(py_aoc_2018.__file__),
            'day_123_input.txt') == get_input_file_path(123)


if __name__ == '__main__':
    pytest.main()
