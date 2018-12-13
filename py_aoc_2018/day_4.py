from __future__ import annotations

from collections import defaultdict
from datetime import date, datetime
from operator import attrgetter
from typing import Generator, List, Union

from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str


class Guard:
    def __init__(self, gid: int) -> None:
        super().__init__()
        self.gid = gid

    @staticmethod
    def from_string(s: str) -> Guard:
        # Guard #2083 begins shift
        return Guard(int(s.strip().split(' ')[1][1:]))

    def __eq__(self, o: object) -> bool:
        if isinstance(o, Guard):
            return self.__dict__ == o.__dict__

        return False

    def __hash__(self) -> int:
        return hash(self.gid)


class Sleep:
    __DEFAULT_END = object()

    def __init__(self, d: date, start: int, end: int = __DEFAULT_END) -> None:
        super().__init__()
        self.date = d
        self.start = start
        self._minutes = None
        if end is self.__DEFAULT_END:
            self._end = 59
            self._is_finished = False
        else:
            self._end = end
            self._is_finished = True

    @property
    def minutes(self):
        if self._minutes is None:
            self._minutes = self._end - self.start
        return self._minutes

    @property
    def end(self):
        return self._end

    @end.setter
    def end(self, end: int):
        self._end = end
        self._minutes = None
        self._is_finished = True

    @property
    def is_finished(self):
        return self._is_finished

    @staticmethod
    def construct(dt: datetime, s: str, sleep: Sleep = None) -> Sleep:
        # [1518-04-20 00:01] falls asleep
        # [1518-03-10 00:28] wakes up
        s = s.strip()
        if s.startswith('falls'):
            return Sleep(dt.date(), dt.minute)
        elif s.startswith('wakes'):
            assert sleep
            assert dt.date() == sleep.date
            sleep.end = dt.minute
            return sleep

    def __eq__(self, o: object) -> bool:
        if isinstance(o, Sleep):
            return o.__dict__ == self.__dict__

        return False


class RawEvent:
    """
    Processes event string into datetime information and rest of the string representation.
    """

    DT_FORMAT = '[%Y-%m-%d %H:%M]'

    def __init__(self, dt: datetime, raw_event: str) -> None:
        super().__init__()
        self.dt = dt
        self.raw_event = raw_event

    def __eq__(self, o: object) -> bool:
        if isinstance(o, RawEvent):
            return o.__dict__ == self.__dict__
        else:
            return False

    def __repr__(self) -> str:
        return f'{datetime.strftime(self.dt, self.DT_FORMAT)} {self.raw_event}'

    @classmethod
    def from_string(cls, s: str) -> RawEvent:
        # [1518-03-19 23:47] Guard #2083 begins shift
        # [1518-04-20 00:01] falls asleep
        # [1518-03-10 00:28] wakes up
        s = s.strip()
        return RawEvent(datetime.strptime(s[0:18], cls.DT_FORMAT), s[19:].strip())


class ItemFactory:
    @staticmethod
    def construct(e: RawEvent, s: Sleep = None) -> Union[Guard, Sleep, None]:
        if e.raw_event.startswith('Guard'):
            return Guard.from_string(e.raw_event)
        elif e.raw_event.startswith('falls'):
            return Sleep.construct(e.dt, e.raw_event)
        elif e.raw_event.startswith('wakes'):
            assert s
            return Sleep.construct(e.dt, e.raw_event, s)
        return None


def process_str_events(events: Generator[str]) -> List[RawEvent]:
    return sorted((RawEvent.from_string(e) for e in events if e.strip()), key=attrgetter('dt'))


def process_raw_events(events: List[RawEvent]) -> defaultdict[Guard, List[Sleep]]:
    guard_sleeps = defaultdict(list)  # type: defaultdict[Guard, List[Sleep]]

    last_guard = None  # type: Guard
    last_sleep = None  # type: Sleep

    for e in events:
        if last_sleep and last_sleep.is_finished:
            # Reset last finished sleep.
            last_sleep = None

        item = ItemFactory.construct(e, last_sleep)

        if isinstance(item, Guard):
            _ = guard_sleeps[item]
            last_guard = item

        elif isinstance(item, Sleep) and not item.is_finished:
            guard_sleeps[last_guard].append(item)
            last_sleep = item

    return guard_sleeps


def day_4():
    with open(get_input_file_path(4), 'r') as f:
        events = stream_lines_as_str(f)

    for e in events:
        pass


    # print(f'Loaded {len(claims)} claims; the canvas has size {size_x}x{size_y}.')

    # too_occupied = SquareBySquareOverclaimedCounter(size_x, size_y, optimize_claims(claims)).count_too_occupied()
    # too_occupied = IterateClaimsOverclaimedCounter(size_x, size_y, optimize_claims(claims)).count_too_occupied()

    # return too_occupied
    pass