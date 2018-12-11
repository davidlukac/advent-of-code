from __future__ import annotations

import abc
import time
from collections import OrderedDict, defaultdict
from operator import attrgetter
from typing import Dict, List, Set, TextIO, Tuple, Union

from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str


class Claim:
    def __init__(self, cid: int, x: int, y: int, size_x: int, size_y: int) -> None:
        self.cid = cid
        self.x = x
        self.y = y
        self.size_x = size_x
        self.size_y = size_y
        self.x_max = x + size_x - 1
        self.y_max = y + size_y - 1

    def is_on(self, x: int, y: int) -> bool:
        if self.x <= x <= self.x_max and self.y <= y <= self.y_max:
            return True
        else:
            return False

    def __eq__(self, o: object) -> bool:
        if isinstance(o, Claim):
            return self.__dict__ == o.__dict__
        else:
            return False

    def __repr__(self) -> str:
        return f'#{self.cid} @ {self.x},{self.y}: {self.size_x}x{self.size_y}'

    @staticmethod
    def from_string(s: str) -> Claim:
        # #1316 @ 818,356: 13x14
        parts = s.strip().split(' ')
        cid = int(parts[0][1:])
        x, y = tuple(int(p) for p in parts[2][:-1].strip().split(','))
        size_x, size_y = tuple(int(p) for p in parts[3].strip().split('x'))

        return Claim(cid, x, y, size_x, size_y)


def load_claims(f: TextIO) -> Tuple[int, int, Dict[int, Claim]]:
    size_x = 0
    size_y = 0
    claims = dict()

    for l in stream_lines_as_str(f):
        c = Claim.from_string(l)
        if size_x < c.x_max:
            size_x = c.x_max
        if size_y < c.y_max:
            size_y = c.y_max
        claims[c.cid] = c

    # Since we're counting the MAX coordinates from zero, we need to +1 when calculating the size of the canvas.
    return size_x + 1, size_y + 1, claims


def optimize_claims(claims: Dict[int, Claim]) -> OrderedDict[int, Claim]:
    return OrderedDict({c.cid: c for c in sorted(claims.values(), key=attrgetter('x', 'y_max'))})


class LogThrottling(metaclass=abc.ABCMeta):
    log_throttle = 5.0

    @abc.abstractmethod
    def set_log_throttle(self, print_throttle):
        pass


class OverclaimedCounter(metaclass=abc.ABCMeta):
    @abc.abstractmethod
    def __init__(self, size_x: int, size_y: int, claims: Dict[int, Claim]) -> None:
        self.size_x = size_x
        self.size_y = size_y
        self.claims = claims

    @abc.abstractmethod
    def count_too_occupied(self) -> int:
        pass


class SquareBySquareOverclaimedCounter(OverclaimedCounter, LogThrottling):
    def __init__(self, size_x: int, size_y: int, claims: Dict[int, Claim]) -> None:
        super().__init__(size_x, size_y, claims)

    def count_too_occupied(self) -> int:
        too_occupied = 0
        it = 0
        sq_checked = 0
        start_time = time.time()
        last_print = start_time
        throughput = 0.0

        for y in range(self.size_y):
            t_row_start = time.time()

            for x in range(self.size_x):
                sq_checked += 1
                xy_occupations = 0

                for cid in list(self.claims.keys()):
                    it += 1

                    if self.claims[cid].is_on(x, y):
                        xy_occupations += 1

                    # Throttle the throttle checking.
                    if it % 100 == 0:
                        t = time.time()
                        if t - last_print >= self.log_throttle:
                            print(f'Currently on {x}x{y} and found {too_occupied} over-claimed sq. inches. '
                                  f'{len(self.claims)} claims remain in the list. '
                                  f'Throughput is {throughput:.2f} sq.inch/s. '
                                  f'Checked {sq_checked} sq.inches so far and {it} items in total.')
                            last_print = t

                    if y > self.claims[cid].y_max:
                        self.claims.pop(cid)

                    if xy_occupations == 2:
                        too_occupied += 1
                        break

            for cid in list(self.claims.keys()):
                if y > self.claims[cid].y_max:
                    self.claims.pop(cid)

            t_row_finish = time.time()
            t_delta = t_row_finish - t_row_start
            if t_delta > 0:
                throughput = self.size_x / t_delta
            else:
                throughput = -1

        end_time = time.time()
        t_delta = end_time - start_time
        if t_delta > 0:
            average_throughput = sq_checked / t_delta
        else:
            average_throughput = -1
        print(f'Average throughput was {average_throughput:.2f} sq.inch/s. '
              f'Checked {sq_checked} sq.inches and {it} items in total.')

        return too_occupied

    def set_log_throttle(self, log_throttle: float):
        self.log_throttle = log_throttle


class ClaimsOverlap:
    def __init__(self, c1: Claim, c2: Claim) -> None:
        super().__init__()
        self.c1 = c1
        self.c2 = c2
        self._is_overlap_on_x = None
        self._overlap_on_x = None
        self._is_overlap_on_y = None
        self._overlap_on_y = None

    @property
    def is_overlap_on_x(self) -> bool:
        if self._is_overlap_on_x is None:
            c1 = self.c1
            c2 = self.c2

            if c2.x < c1.x:
                c1, c2 = c2, c1

            if c2.x <= c1.x_max:
                self._overlap_on_x = max(c1.x, c2.x), min(c1.x_max, c2.x_max)
                self._is_overlap_on_x = True
            else:
                self._is_overlap_on_x = False

        return self._is_overlap_on_x

    @property
    def overlap_on_x(self) -> Union[Tuple[int, int], None]:
        if self.is_overlap_on_x:
            return self._overlap_on_x
        else:
            return None

    @property
    def is_overlap_on_y(self) -> bool:
        if self._is_overlap_on_y is None:
            c1 = self.c1
            c2 = self.c2

            if c2.y < c1.y:
                c1, c2 = c2, c1

            if c2.y <= c1.y_max:
                self._overlap_on_y = max(c1.y, c2.y), min(c1.y_max, c2.y_max)
                self._is_overlap_on_y = True
            else:
                self._is_overlap_on_y = False

        return self._is_overlap_on_y

    @property
    def overlap_on_y(self) -> Union[Tuple[int, int], None]:
        if self.is_overlap_on_y:
            return self._overlap_on_y
        else:
            return None


class IterateClaimsOverclaimedCounter(OverclaimedCounter):
    def __init__(self, size_x: int, size_y: int, claims: Dict[int, Claim]) -> None:
        super().__init__(size_x, size_y, claims)
        self.claims_list = list(self.claims.values())  # type: List[Claim]
        self.claims_len = len(self.claims_list)
        self.claim_ids_not_overlapping = set(self.claims.keys())

    def count_too_occupied(self) -> int:
        t_start = time.time()

        overlapping = {}
        overlapping_ids = set()

        for idx_l in range(self.claims_len):
            claim_l = self.claims_list[idx_l]

            for idx_r in range(idx_l + 1, self.claims_len):
                claim_r = self.claims_list[idx_r]

                overlap = ClaimsOverlap(claim_l, claim_r)

                if claim_l.x_max >= claim_r.x:
                    if overlap.is_overlap_on_x and overlap.is_overlap_on_y:
                        overlapping_ids.add(claim_l.cid)
                        overlapping_ids.add(claim_r.cid)
                        overlapping[
                            overlap.overlap_on_x[0],
                            overlap.overlap_on_x[1],
                            (overlap.c1.cid, overlap.c2.cid)
                        ] = overlap.overlap_on_y
                else:
                    # If the 'next' claim doesn't overlap, we don't need to continue.
                    break

        overlaps_map = defaultdict(set)  # type: defaultdict[int, Set[int]]

        for overlap_tuple, overlap_y in overlapping.items():
            x_start = overlap_tuple[0]
            x_end = overlap_tuple[1] + 1
            y_start = overlap_y[0]
            y_end = overlap_y[1] + 1

            for x in range(x_start, x_end):
                overlaps_map[x].update(range(y_start, y_end))

        overclaimed = len([item for sublist in overlaps_map.values() for item in sublist])

        t_end = time.time()
        delta = t_end - t_start
        if delta > 0:
            sq_in_throughput = self.size_x * self.size_y / delta
            claim_throughput = self.claims_len / delta
        else:
            sq_in_throughput = -1
            claim_throughput = -1

        self.claim_ids_not_overlapping = self.claim_ids_not_overlapping.difference(overlapping_ids)

        print(f'Throughput: {sq_in_throughput:.2f} sq.inches/s and {claim_throughput:.2f} claims/s.')
        print(f'Not overlapping claims are: {self.claim_ids_not_overlapping}.')

        return overclaimed


def day_3() -> int:
    with open(get_input_file_path(3), 'r') as f:
        size_x, size_y, claims = load_claims(f)
    print(f'Loaded {len(claims)} claims; the canvas has size {size_x}x{size_y}.')

    # too_occupied = SquareBySquareOverclaimedCounter(size_x, size_y, optimize_claims(claims)).count_too_occupied()
    too_occupied = IterateClaimsOverclaimedCounter(size_x, size_y, optimize_claims(claims)).count_too_occupied()

    return too_occupied


def main():
    res_too_occupied = day_3()
    print(f"There are {res_too_occupied} sq. inches with 2+ claims.")


if __name__ == '__main__':
    main()
