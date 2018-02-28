cmake_minimum_required(VERSION 3.9)
project(pizza)

set(CMAKE_CXX_STANDARD 17)

add_executable(pizza main.cpp Pizza.cpp Slice.cpp Pizza.h Slice.h)