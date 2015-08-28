# ZF2rapid Installation-Guide

## A. Installation

In the first step you need to install ZF2rapid on your computer. Choose one of 
these installation alternatives

### On Linux with Git

Clone the [Git repository](https://github.com/ZFrapid/zf2rapid.git).

    $ cd /my/path
    $ git clone https://github.com/ZFrapid/zf2rapid.git zf2rapid

### On Linux with `wget`

Get the [ZIP file](https://github.com/ZFrapid/zf2rapid/archive/master.zip) with 
`wget`, unzip its contents and move all files to you prefered path.

    $ cd /any/path
    $ wget --output-document=zf2rapid.zip https://github.com/ZFrapid/zf2rapid/archive/master.zip
    $ unzip zf2rapid.zip 
    $ mv zf2rapid-master/ /my/path/zf2rapid/

### On Windows with Git

Clone the [Git repository](https://github.com/ZFrapid/zf2rapid.git).

    $ git clone https://github.com/ZFrapid/zf2rapid.git c:\zf2rapid

### On Windows with ZIP file

Download the [ZIP file](https://github.com/ZFrapid/zf2rapid/archive/master.zip) 
and unzip it at any temporary location on your computer. Move all files to a 
dir, e.g. `c:\zf2rapid\`. 

## B. Run Composer

In the next step you need to run [Composer](https://getcomposer.org/) to 
install all required packages. If you have not installed Composer yet, please 
refer to the 
[Composer installation guide](https://getcomposer.org/doc/00-intro.md). 

### On Linux

Switch to the new path and run the following `composer` command:

    $ cd /my/path/zf2rapid
    $ composer install

### On Windows

Switch to the new path and run the following `composer` command:

    $ cd c:\zf2rapid
    $ composer install

## C. Test the local installation

In the next step you need to check if the installation was successful by 
running ZF2rapid in your local installation.

### On Linux

Show the ZF2rapid command overview:

    $ cd /my/path/zf2rapid
    $ ./bin/zf2rapid.php

### On Windows

Show the ZF2rapid command overview:

    $ cd c:\zf2rapid
    $ bin\zf2rapid.bat

## D. Setup global usage

In the next step you should setup ZF2rapid to be run globally from every path 
on your computer.

### On Linux

Create a dynamic link in your `/usr/local/bin directory` to you local ZF2rapid 
installation.

    $ sudo ln -s /my/path/zf2rapid/bin/zf2rapid.php /usr/local/bin/zf2rapid

Afterwards switch to any other directory and try to run ZF2rapid.

    $ cd /any/path
    $ zf2rapid

### On Windows

You should add the path to `c:\zf2rapid\bin` to your `PATH` environment 
variable of your Windows configuration and reboot. Afterwards switch to any 
other directory and try to run ZF2rapid.

    $ cd c:\any\path
    $ zf2rapid

## Installation with PHAR file

tbd

