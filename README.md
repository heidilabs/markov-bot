# Markov Bot - Twitter
An open source Twitter bot that uses markov chains to generate tweets. This is basically a simple CLI application written in PHP, 
using the Symfony console. You can run the commands manually or schedule your bot to tweet periodically by adding the tweet command to crontab. 

## Requirements
You need `php` installed. Web server is not required. You also need to register a Twitter APP and get the credentials for the account
you want to post tweets to.

You also need composer to install the project dependencies.

## Quick Installation Instructions

1. Clone this repository
2. Go to the app directory and run `composer install` to install the project dependencies
3. Create your config file by copying `config/config-sample.yml` to `config/config.yml`. Edit the file to set your options, including Twitter credentials.
4. It's ready to use. Run `php console.php` to see the available commands. To test your current configuration without tweeting it, run `php console.php markov:test` .

## Configuring the sources

Currently there are 2 adapters: File and Twitter. You can use as source text files or twitter user timelines.

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