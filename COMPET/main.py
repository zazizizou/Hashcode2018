import numpy as np


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

    return (r,c,f,n,b,t), rides


def distance(ax, ay, bx, by):
    return np.abs(ax - bx) + np.abs(ay - by)


class Car:

    def __init__(self, ride, B):
        self.ride = ride

        self.pos_x = 0
        self.pos_y = 0

        self.ax = int(ride[0])
        self.ay = int(ride[1])
        self.bx = int(ride[2])
        self.by = int(ride[3])
        self.t_start = int(ride[4])
        self.t_finish = int(ride[5])
        self.start_on_time_bonus = int(B)

        self.remaining_distance = distance(self.pos_x, self.pos_y, self.bx, self.by)
        self.distance_to_pickup = distance(self.pos_x, self.pos_y, self.ax, self.ay)

        self.status = "WAITING"

    def go_to_client(self):
        if self.pos_x < self.ax:
            self.pos_x += 1
            self.status = "GOING_TO_CLIENT"
        elif self.pos_y < self.ay:
            self.pos_y += 1
            self.status = "GOING_TO_CLIENT"
        if self.pos_x == self.ax and self.pos_y == self.ay:
            self.status = "ARRIVED!"

    def go_to_destination(self):
        if self.pos_x < self.bx and global_time >= self.t_start:
            self.pos_x += 1
            self.status = "GOING_TO_CLIENT"
        elif self.pos_y < self.by and global_time >= self.t_start:
            self.pos_y += 1
            self.status = "GOING_TO_CLIENT"
        if self.pos_x == self.bx and self.pos_y == self.by:
            self.status = "ARRIVED!"

    def step(self):

        if self.status == "WAITING":
            self.go_to_client()
        elif self.status == "TAKING_CLIENT":
            self.go_to_destination()
        elif self.status == "GOING_TO_CLIENT":
            self.go_to_client()

        if self.status == "ARRIVED!":
            self.status = "WAITING"

        return self.status


def write_assignments(filename, assignments):
    with open(filename, "w") as file:
        for ass in assignments:
            for elm in ass:
                file.write(str(elm) + " ")
            file.write("\n")


def main():

    global_time = 0

    dataset_file = "a_example.in"
    (rows, columns, nb_cars, nb_rides, bonus_per_ride, nb_sim_steps), rides = read_rides(dataset_file)
    all_cars = [Car(ride, bonus_per_ride) for ride in rides]

    print((rows, columns, nb_cars, nb_rides, bonus_per_ride, nb_sim_steps))
    print("rides:", [r for r in rides])

    # distribute initial rides on cars
    all_cars = []
    assignments = []
    rides_counter = 0
    for n in range(int(nb_cars)):
        all_cars += [Car(rides[0], bonus_per_ride)]
        assignments += [[1, n]]
        rides.pop(0)
        rides_counter += 1

    # simulation
    while global_time <= nb_sim_steps:
        for idx, car in enumerate(all_cars):
            if car.step() == "ARRIVED!":
                car(rides[0], bonus_per_ride)
                rides.pop(0)
                assignments[idx][0] += 1
                assignments[idx].append(rides_counter)
                car.status = "WAITING"
        global_time += 1

    write_assignments(dataset_file.replace("in", "out"), assignments)


if __name__ == "__main__":
    main()