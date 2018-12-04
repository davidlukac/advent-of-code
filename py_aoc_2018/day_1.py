from typing import Tuple

from py_aoc_2018.commons import get_input_file_path, stream_lines_as_ints


def day_1() -> Tuple[int, int, int]:
    final_frequency = None
    frequency = 0
    all_frequencies = {0: None}
    matching_frequency = None
    iteration_counter = 0

    while not matching_frequency:
        with open(get_input_file_path(1), 'r') as f:
            for e in stream_lines_as_ints(f):
                frequency += e
                if not matching_frequency:
                    if frequency in all_frequencies:
                        matching_frequency = frequency
                    else:
                        all_frequencies[frequency] = None
        if not final_frequency:
            final_frequency = frequency
        iteration_counter += 1

    return final_frequency, matching_frequency, iteration_counter


def main():
    final_frequency, matching_frequency, iteration_counter = day_1()

    print(f"Final frequency is {final_frequency}.")
    print(f"First matching frequency was {matching_frequency} and it took us just {iteration_counter} iterations.")


if __name__ == '__main__':
    main()
