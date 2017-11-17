
Once you clone it to your machine, make a `composer install` and then execute it with this `php -S localhost:8000 -t web web/index.php `

First of all, I would like to tell you guys that I don't have all the time free that I wish, because I have a 40 hours job and other obligations, and was a challenge to take the hours needed to do this in one week, as you requested. 

I think I did a good job and tried to show my skills in every part of the project. But there is still much I wanted to do and I don't have time, so I will explain it in here.

# Things I would do with more time

### 1. Better class estructure: I focused my efforts in differentiate layers within the applications to make everything organsise. Each layer has it's responsabilities and intend to be totally separated.

controller -> ItemMapper -> HackerNewsClient

However, all the items are ItemNodes. Given some hours more, Id made some more classes. I would made an Item class (most likely abstract) from which many types of classes would extend. They would be the type of items of HackerNews. Story, Comment, Job, Show.... etc.

Ideally I would like this classes to extend another class Abstract (Node) to give them node functionality to work as a tree. But like in PHP there is no multiple inheritance from classes, I would most likely put that in a Trait.

### 2. Requesting users. I dont have a view for showing a single user. So we cannot click in the user and access the page with a user information. 

I speak of this view: https://news.ycombinator.com/user?id=dchuk

I don't have time enough to do that. I am confident that you know that is probably the easiest part on this (just pass information from end to end), and you can see that given the time I could do that.

### 3. Tests. I did one class test as an example. 

I would have love to unit test every class, and I don't work any other way in my day to day. But my focus in here is I could do this in time showing clean code. Also would have love to do integration testing.

### 4. Handle Bootstrap and jQuery with Bower, the package frontend package manager.

I might be forgetting something, but thats basically it. In the same way, if you expected to see anything mention before, just please let me know (and give me some days) and I'd happily do it.


Regarding the HackerNews API, it was a bit tricky requesting information, because many requests needs to be done at the same time. I made that happened as concurrent as I could, but had to limite it a little bit because it was exceding the capacities of the php built-in server. 

- I had tweak the server, but I am not applying for dev-ops, but a software dev, so I tried to sort the limitations with PHP. 

- Also would have been easy to do most of the requests in the frontend, because you can start showing infromation as soon as you get it with callbacks, but the task I received said I have to do it with Silex.

- Could have implement some type of caching for the requests, or even having a cron job storing new items of HackerNews. I saw these solutions exeding the porpuse of this exercise.

Also I have experienced the Hacker News Api not accepting requests if I tried to do to many concurrent requests. So the page is a little bit slow, but not much.

It works internally with items as trees.

To pass the unit tests, just run phpunit in the root folder.

Thanks, any doubt, please contact me.

