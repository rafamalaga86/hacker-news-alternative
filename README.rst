

I had an elegant solution recursive


sacar $maxConcurrentRequests = 150;



disabling issues in hacker news official repo


poner un base url comun

Well, here it is my solution. First of all, I would like to tell you guys that I don't have all the time free that I wish, because I have a 40 hours job and other obligations, and was a challenge to take the hours needed to do this in one week, as you requested. 

I think I did a good job and tried to show my skills in every part of the project. But there is still much I wanted to do and I don't have time, so I will explain it in here.

1. Better class estructure: I focused my efforts in differentiate layers within the applications to make everything organsise.

controller -> ItemMapper -> HackerNewsClient

However, all the items are ItemNodes. Given some hours more, Id made some classes. Id made an Item class (most likely abstract) from which many types of classes would exten. They would be the type of items of HackerNews. Story, Comment, Job, Show.... etc.

Ideally I would like this classes the extend another class Abstract (Node) to give them node functionality to work as a tree. But like in PHP there is no multiple inheritance, I would most likely put that in a Trait.

2. Requesting users. I dont have a view for showing a single user. So we cannot click in the user and access the page with a user information. 

I speak of this view: https://news.ycombinator.com/user?id=dchuk

I don't have time enough to do that. I am confident that you know that is probably the easiest part on this (just pass information from end to end), and can see that given the time I could do that.

3. Tests. I did one class test as an example. I would have love to unit test every class, and I don't work any other way in my day to day. But my focus in here is I could do this in time showing clean code. Also would have love to do integration testing.

4. Handle Bootstrap and jQuery with Bower, the package frontend package manager.

I might be forgetting something, but thats basically it. In the same way, if you expected to see anything mention before, just please let me know (and give me some days) and I'd happily do it.

