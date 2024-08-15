### Foodics Challenge


## Content: 
- [Problem Statement](#problem-statement)
- [Solution](#solution)
- [Improvements](#improvements)
- [How to run the code](#how-to-run-the-code)
- [How to run the tests](#how-to-run-the-tests)


## Problem Statement
> we are designing a system that updates ingredient stock levels, and alerts staff when supplies run low, to ensure that we have enough ingredients to make our burgers. The system should also be able to process orders and update the stock levels of ingredients accordingly.

## Solution
- We solved this by making an api call that creates an order, sends the order details.
- Then we dispatch a batch that contain two jobs, one to update the stock level and the other to send an alert if the stock level is low.



## Improvements
- We have a multi unit database design, like liter, kilogram, gram, etc which adds more flexibility to the system.
- Each unit has a conversion factor to convert it to the base unit.
- Each unit has a type either (volume, weight, or piece).



