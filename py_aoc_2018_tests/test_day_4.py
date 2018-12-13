import io
import unittest
from collections import defaultdict
from datetime import date, datetime

import pytest

from py_aoc_2018.commons import stream_lines_as_str
from py_aoc_2018.day_4 import Guard, RawEvent, Sleep, calculate_weakness, process_raw_events, process_str_events
from py_aoc_2018.day_4 import find_best_guard


class TestSleep(unittest.TestCase):
    def test_sleep(self):
        s = Sleep(date(2015, 10, 13), 10, 15)
        assert s
        assert s.date == date(2015, 10, 13)
        assert s.minutes == 5

        s = Sleep(date(2015, 10, 13), 55)
        assert s.end == 59
        assert s.minutes == 4

    def test_sleep_setter(self):
        s = Sleep(date(2015, 10, 13), 55)
        assert s.end == 59
        assert s.minutes == 4
        s.end = 58
        assert s.end == 58
        assert s.minutes == 3

    def test_sleep_construct(self):
        s = Sleep.construct(datetime(2015, 10, 13, 0, 55), 'falls asleep')
        assert s.date == date(2015, 10, 13)
        assert s.start == 55
        assert s.end == 59
        assert s.minutes == 4

        s = Sleep.construct(datetime(2015, 10, 13, 0, 58), 'wakes up', s)
        assert s.date == date(2015, 10, 13)
        assert s.start == 55
        assert s.end == 58
        assert s.minutes == 3

    def test_sleep_is_finished(self):
        s = Sleep.construct(datetime(2015, 10, 13, 0, 55), 'falls asleep')
        assert not s.is_finished
        assert s.end == 59
        assert s.minutes == 4
        s = Sleep.construct(datetime(2015, 10, 13, 0, 58), 'wakes up', s)
        assert s.is_finished
        assert s.end == 58
        assert s.minutes == 3

    def test_sleep_equals(self):
        s1 = Sleep(date(2015, 12, 3), 12)
        s2 = Sleep(date(2015, 12, 3), 12)
        assert s1 == s2

        s1 = Sleep(date(2015, 12, 3), 12)
        s2 = Sleep(date(2015, 12, 3), 13)
        assert s1 != s2

        s1 = Sleep(date(2015, 12, 3), 12)
        s2 = Sleep(date(2015, 12, 3), 12, 59)
        assert s1 != s2

        s1 = Sleep(date(2015, 12, 3), 12, 20)
        s2 = Sleep(date(2015, 12, 3), 12, 20)
        assert s1 == s2


class TestRawEvent(unittest.TestCase):
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

    def test_raw_event_repr(self):
        re = RawEvent.from_string('[1518-11-01 00:25] wakes up')
        assert re.__repr__() == '[1518-11-01 00:25] wakes up'

    def test_raw_event_equal(self):
        re1 = RawEvent.from_string('[1518-11-01 00:00] Guard #10 begins shift')
        re2 = RawEvent.from_string('[1518-11-01 00:00] Guard #10 begins shift')
        assert re1 == re2

    def test_raw_event_processing(self):
        data = """
[1518-11-01 00:25] wakes up
[1518-11-01 00:55] wakes up
[1518-11-01 00:05] falls asleep
[1518-11-01 00:30] falls asleep
[1518-11-01 00:00] Guard #10 begins shift
[1518-11-01 23:58] Guard #99 begins shift
[1518-11-02 00:40] falls asleep
[1518-11-02 00:50] wakes up
[1518-11-03 00:05] Guard #10 begins shift
[1518-11-03 00:29] wakes up
[1518-11-03 00:24] falls asleep
[1518-11-04 00:02] Guard #99 begins shift
"""
        r5 = RawEvent.from_string('[1518-11-01 00:00] Guard #10 begins shift')
        r3 = RawEvent.from_string('[1518-11-01 00:05] falls asleep')
        r1 = RawEvent.from_string('[1518-11-01 00:25] wakes up')
        r4 = RawEvent.from_string('[1518-11-01 00:30] falls asleep')
        r2 = RawEvent.from_string('[1518-11-01 00:55] wakes up')
        r6 = RawEvent.from_string('[1518-11-01 23:58] Guard #99 begins shift')
        r7 = RawEvent.from_string('[1518-11-02 00:40] falls asleep')
        r8 = RawEvent.from_string('[1518-11-02 00:50] wakes up')
        r9 = RawEvent.from_string('[1518-11-03 00:05] Guard #10 begins shift')
        r11 = RawEvent.from_string('[1518-11-03 00:24] falls asleep')
        r10 = RawEvent.from_string('[1518-11-03 00:29] wakes up')
        r12 = RawEvent.from_string('[1518-11-04 00:02] Guard #99 begins shift')

        stream = io.StringIO(data)
        sorted_events = process_str_events(stream_lines_as_str(stream))
        expected = [
            r5, r3, r1, r4, r2, r6, r7, r8, r9, r11, r10, r12
        ]

        assert len(expected) == len(sorted_events)
        assert sorted_events == expected


class TestGuard(unittest.TestCase):
    def test_guard(self):
        g1 = Guard(10)
        g2 = Guard(10)
        assert g1 == g2
        assert g1.gid == 10
        g2 = Guard(20)
        assert g1 != g2

        g1 = Guard(10)
        g2 = Guard(10)
        d = dict()
        d[g1] = None
        d[g2] = None
        assert len(d.keys()) == 1
        assert list(d.keys())[0].gid == 10

        g1 = Guard(10)
        g2 = Guard(20)
        d = dict()
        d[g1] = None
        d[g2] = None
        assert len(d.keys()) == 2
        assert list(d.keys())[0].gid == 10
        assert list(d.keys())[1].gid == 20

    def test_guard_factory(self):
        g1 = Guard.from_string(' Guard #2083 begins shift')
        assert Guard(2083) == g1
        assert g1.gid == 2083


class TestDay4(unittest.TestCase):
    def test_process_raw_events(self):
        r1 = RawEvent.from_string('[1518-11-01 00:00] Guard #10 begins shift')
        r2 = RawEvent.from_string('[1518-11-01 00:05] falls asleep')
        r3 = RawEvent.from_string('[1518-11-01 00:25] wakes up')
        r4 = RawEvent.from_string('[1518-11-01 00:30] falls asleep')
        r5 = RawEvent.from_string('[1518-11-01 00:55] wakes up')
        r6 = RawEvent.from_string('[1518-11-02 23:58] Guard #99 begins shift')
        r7 = RawEvent.from_string('[1518-11-03 00:40] falls asleep')
        r8 = RawEvent.from_string('[1518-11-03 00:50] wakes up')
        r9 = RawEvent.from_string('[1518-11-03 23:58] Guard #999 begins shift')

        res = process_raw_events([r1, r2, r3, r4, r5, r6, r7, r8, r9])

        g10 = Guard(10)
        g99 = Guard(99)
        g999 = Guard(999)

        s1 = Sleep(date(1518, 11, 1), 5, 25)
        s2 = Sleep(date(1518, 11, 1), 30, 55)
        s3 = Sleep(date(1518, 11, 3), 40, 50)

        d = defaultdict(list)
        d[g10] = [s1, s2]
        d[g99] = [s3]
        d[g999] = []

        assert res == d

    def test_calculate_weakness(self):
        s1 = Sleep(date(1518, 11, 1), 5, 25)
        s2 = Sleep(date(1518, 11, 1), 30, 55)
        s3 = Sleep(date(1518, 11, 3), 40, 50)

        assert (55, 40) == calculate_weakness([s1, s2, s3])

    def test_find_best_guard(self):
        data = """
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

        stream = io.StringIO(data)
        sorted_events = process_str_events(stream_lines_as_str(stream))
        guard_sleeps = process_raw_events(sorted_events)
        assert (Guard(10), 50, 24) == find_best_guard(guard_sleeps)


if __name__ == '__main__':
    pytest.main()
