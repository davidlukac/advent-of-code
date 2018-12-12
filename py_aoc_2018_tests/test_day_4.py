import pytest
import unittest
import io
from py_aoc_2018.commons import get_input_file_path
from py_aoc_2018.day_4 import Sleep, RawEvent
from datetime import datetime, date


class TestDay4(unittest.TestCase):
    def test_event(self):
        e = Sleep(date(2015, 10, 13), 10, 15)
        assert e
        assert e.d == date(2015, 10, 13)
        assert e.minutes == 5

    def test_raw_event(self):
        re = RawEvent.from_string('[1518-11-01 00:00] Guard #10 begins shift')
        assert re.dt == datetime(1518, 11, 1, 0, 0)
        assert re.raw_event == 'Guard #10 begins shift'

        re = RawEvent.from_string('[1518-11-01 00:05] falls asleep')
        assert re.dt == datetime(1518, 11, 1, 0, 5)
        assert re.raw_event == 'falls asleep'

        re = RawEvent.from_string('[1518-11-01 00:25] wakes up')
        assert re.dt == datetime(1518, 11, 1, 0, 25)
        assert re.raw_event == 'wakes up'

    def test(self):
        data = [
            """
[1518-11-01 00:00] Guard #10 begins shift
[1518-11-01 00:05] falls asleep
[1518-11-01 00:25] wakes up
[1518-11-01 00:30] falls asleep
[1518-11-01 00:55] wakes up
[1518-11-01 23:58] Guard #99 begins shift
[1518-11-02 00:40] falls asleep
[1518-11-02 00:50] wakes up
[1518-11-03 00:05] Guard #10 begins shift
[1518-11-03 00:24] falls asleep
[1518-11-03 00:29] wakes up
[1518-11-04 00:02] Guard #99 begins shift
[1518-11-04 00:36] falls asleep
[1518-11-04 00:46] wakes up
[1518-11-05 00:03] Guard #99 begins shift
[1518-11-05 00:45] falls asleep
[1518-11-05 00:55] wakes up
            """
        ]

        stream = io.StringIO('\n'.join(data))
        assert (7, 7) == load_claims(stream)[:-1]


if __name__ == '__main__':
    pytest.main()
