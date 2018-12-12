from __future__ import annotations
from datetime import datetime, date
from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str
from typing import List, Generator


class Guard:
    def __init__(self, gid: int) -> None:
        super().__init__()
        self.gid = gid


class Sleep:
    def __init__(self, d: date, start: int, end: int) -> None:
        super().__init__()
        self.d = d
        self.start = start
        self.end = end
        self._minutes = None

    @property
    def minutes(self):
        if self._minutes is None:
            self._minutes = self.end - self.start
        return self._minutes


class RawEvent:
    def __init__(self, dt: datetime, raw_event: str) -> None:
        super().__init__()
        self.dt = dt
        self.raw_event = raw_event

    @staticmethod
    def from_string(s: str) -> RawEvent:
        # [1518-03-19 23:47] Guard #2083 begins shift
        # [1518-04-20 00:01] falls asleep
        # [1518-03-10 00:28] wakes up
        s = s.strip()
        return RawEvent(datetime.strptime(s[0:18], '[%Y-%m-%d %H:%M]'), s[19:].strip())


class RawEventLog:
    def __init__(self) -> None:
        super().__init__()



def load_events(f) -> Generator[str]:
    stream_lines_as_str(f)

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