import os
from typing import Generator, TextIO


def get_input_file_path() -> str:
    return os.path.join(
        os.path.realpath(os.path.dirname(__file__)),
        'day_1_input.txt'
    )


def stream_lines(f: TextIO) -> Generator[int, None, None]:
    while True:
        line = f.readline()
        if not line:
            break
        yield int(line)


def main():
    with open(get_input_file_path(), 'r') as f:
        frequency = sum(stream_lines(f))
    print(f"Final frequency is {frequency}.")


if __name__ == '__main__':
    main()
