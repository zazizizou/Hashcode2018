import numpy as np


class Pizza:

    def __init__(self, r, c, l, h, content=np.array([], dtype="str")):
        self.R = r
        self.C = c
        self.L = l
        self.H = h
        self.content = content


def get_tom_mush_number(content):
    flat_content = content.flatten()
    l_mush = l_tom = 0
    for ingr in flat_content:
        if ingr == "T":
            l_tom += 1
        elif ingr == "M":
            l_mush += 1
        else:
            print("ERROR: ingredient not valid!")
    return l_tom, l_mush


class Slice:

    def __init__(self, a, b, pizza):
        self.ax = a[0]
        self.ay = a[1]
        self.bx = b[0]
        self.by = b[1]
        self.content = pizza.content[a[0]:(b[0]+1), a[1]:(b[1]+1)]

        self.l_tom, self.l_mush = get_tom_mush_number(self.content)
        self.h = self.content.size


def merge_slices(r1, r2, pizza):
    ax = min(r1.ax, r2.ax)
    ay = min(r1.ay, r2.ay)
    bx = max(r1.bx, r2.bx)
    by = max(r1.by, r2.by)
    pizza.content = pizza.content[ax:(bx+1), ay:(by+1)]
    return Slice((ax,ay), (bx,by), pizza)


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


def are_neighbours(r1, r2):
    if np.abs(r2.ax - r1.ax) == (r1.bx - r1.ax + 1):
        return True
    elif np.abs(r2.ay - r1.ay) == (r1.by - r1.ay + 1):
        return True
    else:
        return False


def cut(pizza, nb_iterations=10):
    L = pizza.L
    H = pizza.H

    # get initial regions
    regions = []
    regions_removed = set()
    for i, _ in enumerate(pizza.content):
        for j, _ in enumerate(pizza.content):
            new_content = np.reshape(pizza.content[i,j], (1,1))
            new_pizza = Pizza(pizza.R, pizza.C, pizza.L, pizza.H, new_content)
            regions += [
                Slice((i, j), (i, j), new_pizza)
            ]

    regions_one_iter = []
    print([r.content for r in regions])
    for r1 in regions:
        print("content of r1: ", r1.content)
        for r2 in regions:
            if r1 != r2 and are_neighbours(r1, r2) and r1.content != r2.content:
                merged = merge_slices(r1, r2, pizza)
                regions_removed.add(r1)
                regions_removed.add(r2)
                regions_one_iter += [merged]
                print("added merged!")

    regions_final = [r for r in regions_one_iter]
    # regions_final = [r for r in regions if r not in regions_removed]
    return regions_final


def write_result(regions_final, filename):
    with open(filename, "w") as file:
        file.write(str(len(regions_final)) + "\n")
        for region in regions_final:
            file.write((str(region.ax)+ " "+
                        str(region.ay)+ " "+
                        str(region.bx)+ " "+
                        str(region.by)+ " " + "\n"))


def main():
    content = np.array([["T", "T", "T", "T", "T"],
                        ["T", "M", "M", "M", "T"],
                        ["T", "T", "T", "T", "T"]])

    pizza = Pizza(3, 5, 1, 6, content)
    print(pizza.content)

    pizza = pizza_read("small.in")
    print("Content after reading")
    print(pizza.content)
    assert pizza.C == 7
    assert pizza.R == 6

    myslice = Slice((0,0), (1,1), pizza)
    print("new slice content:")
    print(myslice.content)
    assert myslice.h == 4
    assert myslice.l_mush == 3
    assert myslice.l_tom == 1

    res = merge_slices(Slice((0,0), (1,1), pizza), Slice((0,0), (2,2), pizza), pizza)
    print(res.content)
    regions = cut(pizza)
    write_result(regions, "small.out")


if __name__ == "__main__":
    main()