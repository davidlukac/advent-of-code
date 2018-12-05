from py_aoc_2018.commons import get_input_file_path, stream_lines_as_str


class Claim:
    def __init__(self, cid: int, x: int, y: int, size_x: int, size_y: int) -> None:
        self.cid = cid
        self.x = x
        self.y = y
        self.size_x = size_x
        self.size_y = size_y

    def __eq__(self, o: object) -> bool:
        if isinstance(o, Claim):
            if o.cid == self.cid and \
                    o.x == self.x and \
                    o.y == self.y and \
                    o.size_x == self.size_x and \
                    o.size_y == self.size_y:
                return True
            else:
                return False
        else:
            return False

    @staticmethod
    def from_string(s: str):
        # #1316 @ 818,356: 13x14
        parts = s.strip().split(' ')
        cid = int(parts[0][1:])
        x, y = tuple(int(p) for p in parts[2][:-1].strip().split(','))
        size_x, size_y = tuple(int(p) for p in parts[3].strip().split('x'))

        return Claim(cid, x, y, size_x, size_y)


def day_3():
    with open(get_input_file_path(3), 'r') as f:
        for l in stream_lines_as_str(f):
            pass
