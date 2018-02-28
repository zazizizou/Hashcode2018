import numpy as np


class Pizza:

    def __init__(self, r, c, l, h, content=np.array([], dtype="str")):
        self.R = r
        self.C = c
        self.L = l
        self.H = h
        self.content = content
        print("initialized pizza")


def get_tom_mush_number(content):
    l_mush = l_tom = 0
    for row in content:
        for ingr in row:
            if ingr == "T":
                l_tom += 1
            elif ingr == "M":
                l_mush += 1
            else:
                print("ERROR: ingredient not valid!")
    return l_tom, l_mush


class Slice:

    def __init__(self, a, b, content):
        self.ax = a[0]
        self.ay = a[1]
        self.bx = b[0]
        self.by = b[1]
        self.content = content

        self.l_tom, self.l_mush = get_tom_mush_number(content)
        self.h = content.size()


def pizza_read(filename):
    with open(filename, "r") as file:
        param = file.readline().split(" ")
        R = int(param[0])
        C = int(param[1])
        L = int(param[2])
        H = int(param[3])

        content = np.empty((R, C), dtype="str")
        for i, line in enumerate(file):
            for j, ingr in enumerate(line.replace("\n", "")):
                content[i][j] = ingr
    return Pizza(R, C, L, H, content)


def get_slice(pizza, a, b):
    slice_content = pizza.content[a[0]:b[0]][a[1]:b[1]]
    slice = Slice(a, b, slice_content)
    return slice


def cut(pizza):
    # get initial regions
    regions_init = []
    for i, _ in enumerate(pizza.content):
        for j, _ in enumerate(pizza.content):
            slice_content = pizza.content[i][j]
            regions_init += [Slice((i, j), (i, j), slice_content)]




def main():
    content = np.array([["T", "T", "T", "T", "T"],
                        ["T", "M", "M", "M", "T"],
                        ["T", "T", "T", "T", "T"]])

    pizza = Pizza(3, 5, 1, 6, content)
    print(pizza.content)

    pizza.read("small.in")
    print("Content after reading")
    print(pizza.content)
    assert pizza.C == 7
    assert pizza.R == 6




if __name__ == "__main__":
    main()




