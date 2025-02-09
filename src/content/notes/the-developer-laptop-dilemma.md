---
title: "The Developer's Laptop Dilemma - MacBook or Windows with Snapdragon Elite X"
description:
  "What is the next laptop, a MacBook or a Windows ARM laptop which says it can match the MacBook."
slug: "macbook-vs-windows-arm"
started: "2025-02-09"
updated: "2025-02-09"
cover: "~/assets/macbook-vs-surface.png"
topics:
  - "MacBook"
  - "Windows ARM"
  - "Snapdragon Elite X"
---

I was about to buy a new laptop and had a difficult decision to make. What the hell should I buy? I
am a long-time Windows user, both privately and at work. I did all my management work and
development with WSL2 on Windows. Two months ago, I switched to a MacBook for work to try it out.
Now I had to make a decision for a new laptop.

## Juggling Between Macs and Windows Laptops

I love the MacBook Pro M1. It has great build quality, a nice display and sound, amazing battery
life, and everything works smoothly. During the two months on the M1, I found a lot of little
annoyances that would make me go back to Windows. But the battery life is so great on a MacBook.
Imagine working all day on your machine, having online meetings in Teams, and even having some
battery left for the next morning.

As a frontend and backend developer using VSCode and Docker, I do not need a very fast machine.
Enough memory is more important. I do have a Lenovo ThinkPad X1 Carbon, which is fast, but the
battery sucks and if you do anything other than reading a page in a browser, the fans start. Doing
something that requires a lot of CPU and you can't type anymore because the laptop turns into a
heater.

For my work, I used a Dell XPS 13 Plus, which has the same issues as the X1. It gets hot and loud if
you start a Teams meeting. Besides that, the function keys are weird. They feel just wrong without
haptic feedback. You have no idea if you pressed one. The top right power button feels like it is
broken when you press it. And then there is the glass touchpad, which is announced as a highlight. I
can tell you it is the opposite. After over one year of use, I still cannot get used to it, let
alone find it. It is big, but there is no indication of where it is located. Without knowing it you
are touching it with two hands and you are using a gesture you did not planned.

So what if I could get the advantages of a Windows machine and a MacBook together in one? I guess
the first solution would be to install Windows on a MacBook. I read it is possible, but I am not
convinced it will give me the experience I am looking for.

## Embracing ARM: The Big Decision

Fortunately, the internet is blowing up with announcements of the Snapdragon X Elite CPU. It is
ARM-based, should be reasonably fast, and has the battery life of a MacBook. It sounds too good to
be true and while Windows ARM exists for years already, it never really took off to the masses
because of the lack of good hardware.

I have seen quite some reviews, both written and on YouTube. They did look promising. But taking
that step was huge. What if it does not live up to it? It could be a disaster. And then there are
the warnings around the internet that software is not running on ARM, or best case it is emulated
which is less efficient and consumes more power. On Reddit people are skeptical because so far the
ARM Windows experience has not been a good one.

Looking at the site [windowsonarm.org](https://windowsonarm.org/), I found out that all apps I use
are already running on ARM natively. The list is pretty long actually. Reviews and some benchmarks
show that the performance is pretty good, somewhere between a MacBook M3 and M4. Gaming is expected
to be an issue, but I don't play much anyway, and if I do it is mostly Factorio. Besides the battery
life, another advantage should be that it is silent, as in no fans running when you do something
serious. Most Intel laptops will be throttled down in performance to keep the heat under control. I
know that on my X1 and XPS I need to set performance to "Best Power Efficiency" to keep it silent
and cool.

I was not 100% convinced it would be the right decision, but I took it anyway. A Microsoft Surface
Laptop 7 with the Snapdragon Elite X 12 Core and 32 GB it is.

Other ones I looked at were the Dell XPS 13 and a Lenovo ThinkPad with the same Snapdragon
processor, but given my experience with the XPS it was a no-go. Same build, but with a Snapdragon
inside. The Lenovo was disqualified because I think the quality overall feels cheaper. The Surface
should have a similar build quality as a MacBook.

## Hands-On with the Surface Laptop 7

Two days later the doorbell rang and I received my new laptop. My first impression after unpacking
it was light, good build quality, sturdy, and nice looking.

Installing the software I need was easy. I used winget from within PowerShell, and it automatically
installed all my apps in an ARM version if available. Obsidian, VSCode, Spotify, ARC browser,
Docker, Teams, Outlook. WSL… all there in ARM.

After setting up the machine, configuring it, and charging it, I started playing with it. The sound
is nice. Not as good as a MacBook, but better than the X1 and XPS for sure. The speakers are located
behind the keyboard somewhere. Also, the microphone and camera are good, which I tested in a Teams
meeting. Surprisingly the laptop kept quiet and did not heat up.

Everything feels smooth and fast enough for me. What surprised me was the really good build quality.
Microsoft did a good job there. Also starting up the machine when opening the lid and logging in via
facial recognition is amazingly fast. Like almost instantly. I have not seen that on any Intel-based
Windows machine.

How about the architecture the apps are running on? Looking at the Task Manager details tab, it
shows what is running on ARM and what is emulated on x64 and x86. There are two processes not
running as ARM. MicrosoftSecurityApp.exe is running as x64, and once in a while a dllhost process is
popping up on the list as an x86 architecture process. Not bad I would say.

So how about that battery life, is it really as promised? It is soon to tell, but I did charge it
two days ago and I am still using it right now. The battery percentage is down to 26%. I can see
that it is slowly draining, I would estimate around 7% per hour. That would give me about 14 hours
of usage. During that time I did some coding in VSCode, was running Docker, surfed the web, had
chats and a meeting in Teams, listened to music on Spotify, and was writing this article in
Obsidian. I can live with this if the laptop lasts for at least a day. It seems to be more, but time
will tell.

## TL;DR

Overall, good build quality, nice sound, good performance, and the amazing battery life I was hoping
for. All software I need to be productive is natively running on the ARM architecture. I'm happy so
far with my new machine.

Microsoft and Qualcomm developed a good product with the Surface Laptop 7. For me there is no need
anymore to think about a MacBook.
