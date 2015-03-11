# Markov Bot - Twitter
An open source Twitter bot that uses markov chains to generate tweets. This is basically a simple CLI application written in PHP, 
using the Symfony console. You can run the commands manually or schedule your bot to tweet periodically by adding the tweet command to crontab. 

## Requirements

- PHP (cli only, no web server needed)
- [Composer](https://getcomposer.org/)
- Twitter APP, and proper credentials: two keys for the app, and two keys for the user account in which the tweets are going to be posted. 
For detailed instructions, check [this tutorial](http://twilex.readthedocs.org/en/latest/app_creation.html). The user tokens can be obtained
in the APP settings page.

## Quick Installation Instructions

1. Clone this repository
2. Go to the app directory and run `composer install` to install the project dependencies
3. Create your config file by copying `config/config-sample.yml` to `config/config.yml`. Edit the file to set your options, including Twitter credentials.
4. It's ready to use. Run `php console.php` to see the available commands. To test your current configuration without tweeting it, run `php console.php markov:test` .

## Commands

### twitter:test
Test the current Twitter settings and outputs which user is connected and would have the updates posted to, according to the provided keys.

```bash
    $ php console.php twitter:test
```

### markov:test
Test the current bot settings and outputs an example of tweet that would be posted using this settings.

```bash
    $ php console.php markov:test
```

### markov:tweet
Posts an update to Twitter using the current settings. The tweet is also outputted. 

```bash
    $ php console.php markov:tweet
```

### cache:update
Updates the cached samples for Twitter and RSS feeds.  

```bash
    $ php console.php cache:update
```

## Configuring the sources

Currently there are 3 adapters: File, Twitter and RSS. The contents are cached in the first run, and you can run a command to update this cache if you want.
It might be interesting to add it to crontab to update once a day, for instance.

### Methods
- wordchain: this will generate a word chain based on the samples you provide. A wordchain basically groups the text into parts of 2 words and try to find, 
randomly, a complement for each link of the chain. This method works better when you have more content as sample.
- mixedsource: this method is funny because it mixes two different sources, trying to use a common point of intersection between two sentences.

### Sources
Sources can come from a text file, a twitter account, or a RSS feed. The contents are cached locally in a simple txt file. You can update the samples anytime by
running ``php console.php cache:update``. The sources are defined using a prefix, followed by the path (in case of local file), url without protocol prefix(in case of RSS) or username (in case of Twitter sources).

- file://path/to/file.txt
- twitter://erikaheidi
- rss://feeds.gawker.com/gizmodo/full

Note: RSS with full content instead of only titles and descriptions work better.

### Examples

Using wordchain based on text files

```yml
    markov.settings:
        method: wordchain
        sources:
            - file://data/nietzsche.txt
            - file://data/linux.txt
```

> as a charm, that one should be heartily ashamed. To lose the intuition as to the EXTENT to which a process belongs

Using mixedsource based on text files

```yml
    markov.settings:
        method: mixedsource
        sources:
            - file://data/nietzsche.txt
            - file://data/mj.txt
``` 
           
> riddles which the conflicting nature at the basis of the kid is not my son She says I am the

Using mixedsource based on 2 different twitter users

```yml
    markov.settings:
        method: mixedsource
        sources:
            - twitter://erikaheidi
            - twitter://digitalocean
``` 
           
> Oh yes. Packing has started! @digitalocean @phpbenelux what a day... setup: Dive into service discovery and learn how it works
        
Using mixedsource with twitter and text file

```yml
    markov.settings:
        method: mixedsource
        sources:
            - twitter://erikaheidi
            - file://data/mj.txt
```
          
> marvelous. well done @ChasingUX , well done. So excited to always think twice (do think twice.) She told my baby
            
Using mixedsource with twitter and rss feed

```yml
    markov.settings:
        method: mixedsource
        sources:
            - twitter://erikaheidi
            - rss://feeds.gawker.com/gizmodo/full
```
          
> magician show at #phpbnl15 closing. By the way: I have missed, and it's all on BitStream.... The changing color of


## Managing multiple bots

You can run multiple bots within the same installation, for that you just need to create other config files and place them inside your "config" folder.
Then, when calling the **markov** commands, you should provide the ``--config`` option, passing the name of the file as parameter. 

Example:

```bash
    $ php console.php twitter:test --config=other.yml
    $ php console.php twitter:tweet --config=other.yml
```