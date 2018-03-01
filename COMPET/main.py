import numpy as np

global_time = 0


def read_rides(filename):
    with open(filename, "r") as file:
        param = file.readline().replace("\n", "").split(" ")
        r = int(param[0])
        c = int(param[1])
        f = int(param[2])
        n = int(param[3])
        b = int(param[4])
        t = int(param[5])

        rides = []
        for line in file:
            rides += [line.replace("\n", "").split(" ")]

    return param, rides


class Car:

    def __init__(self, ride, B):
        self.ax = int(ride[0])
        self.ay = int(ride[1])
        self.bx = int(ride[2])
        self.by = int(ride[3])
        self.t_start = int(ride[4])
        self.t_finish = int(ride[5])
        self.start_on_time_bonus = int(B)

        self.remaining_distance = np.abs(self.ax - self.bx) + np.abs(self.ay - self.by)

    def step(self):
        self.remaining_distance -= 1
        if self.remaining_distance <= 0:
            return "finished"
        else:
            return "busy"



def main():
    dataset_file = "b_should_be_easy.in"
    dataset_file = "a_example.in"
    (R, C, F, N, B, T), rides = read_rides(dataset_file)
    all_cars = [Car(ride, B) for ride in rides]

    print((R, C, F, N, B, T))
    print("rides:", [r for r in rides])




if __name__ == "__main__":
    main()