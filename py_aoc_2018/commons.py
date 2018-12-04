import os
from typing import Generator, TextIO


def get_input_file_path(day_number: int) -> str:
    return os.path.join(
        os.path.realpath(os.path.dirname(__file__)),
        f'day_{day_number}_input.txt'
    )


def stream_lines_as_ints(f: TextIO) -> Generator[int, None, None]:
    while True:
        line = f.readline()
        if not line:
            break
        yield int(line)


def stream_lines_as_str(f: TextIO) -> Generator[str, None, None]:
    while True:
        line = f.readline()
        if not line:
            break
        yield str(line)

