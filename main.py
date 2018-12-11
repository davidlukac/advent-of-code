import sys
from importlib import import_module

if __name__ == '__main__':
    day = int(sys.argv[1])
    day_str = f'day_{day}'
    getattr(import_module(f'py_aoc_2018.day_{day}'), 'main')()
