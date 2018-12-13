from py_aoc_2018.commons import get_input_file_path


def is_opposite(c1: str, c2: str) -> bool:
    if len(c1) == 1 and len(c2) == 1 and c1.lower() == c2.lower():
        if c1.islower() and c2.isupper() or c1.isupper() and c2.islower():
            return True
        else:
            return False
    else:
        return False


def reduce(s: str) -> str:
    still_reducing = True
    source = s
    result = ''

    while still_reducing:
        still_reducing = False
        result = ''
        source_l = len(source)

        i = 0
        while i < source_l:
            c_current = source[i]

            if i < source_l - 1:
                c_next = source[i + 1]
                if is_opposite(c_current, c_next):
                    still_reducing = True
                    i += 2
                    continue
                else:
                    result += c_current
            else:
                result += c_current

            i += 1

        source = result

    return result


def main():
    with open(get_input_file_path(5), 'r') as f:
        in_str = f.readline().strip()

    print(f'Result is: {len(reduce(in_str))}.')


if __name__ == '__main__':
    main()
