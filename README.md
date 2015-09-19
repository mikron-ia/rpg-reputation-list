# RPG Reputation List
Universal reputation collection and display system for RPG stories

## Background
In RPGs, there are several ways of rewarding characters (and players). Experience is the most common, magical (or just high-quality, dependent on the setting) items, allies or titles are also available. Reputation is another of such rewards - useful to the GM, due to being a double-edged sword (famous characters tend to get in harm's way).

Unfortunatelly, reputation in most systems is either ignored except for some informal titles / perks / aspects, or given a complex system involving numbers. The former case is easy to use, but not very useful, the latter - if made well - is useful, but hard to manage. This application is supposed to solve the "hard to manage" part.

## Planned features
* Ability to create "people" to attach reputations to; those may be organisations or objects as well
* Ability to add, edit & remove events that influence reputation
* System that manages the event-connected reputation, sums it up and displays it, along with potential attached strings, like: titles, perks, fame levels, threats, and side effects

### Assumptions
* MVF will be purely a GM tool, without anything but maybe basic authorisation; it will deliver all data to everyone
    * Initial data output can be crude JSON put out to the screen - backend logic is more important ATM
* The application **should** be functional before appearing nice - I could use it now
* The application **should** be system-agnostic; configuration (essentially a list of reputation networks / societes) will be put in config files

### Nice to have
* Login system that presents the situation from single character's perspective - not everyone knows of every deed

## Development
### Technologies
Backend written in PHP and based on Silex, storage either in MySQL or MongoDB (no research has been done yet), frontend will be likely written in AngularJS (unless I learn Ember in the meantime and decide it is better).

## Installation guide
... will be created once the project does anything except passing tests.