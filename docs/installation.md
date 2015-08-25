# ZF2rapid Installation-Guide

## Installation on Linux with Git and Composer

Clone the [Git repository](https://github.com/ZFrapid/zf2rapid.git).

    $ git clone https://github.com/ZFrapid/zf2rapid.git zf2rapid

Switch to the new path and run the following `composer` command:

    $ cd /my/zf2rapid/path
    $ composer install

Show the ZF2rapid command overview:

    $ ./bin/zf2rapid.php

## Installation on Linux with `wget` and Composer

Get the [ZIP file](https://github.com/ZFrapid/zf2rapid/archive/master.zip) with 
`wget`, unzip its contents and move all files to you prefered path.

    $ wget --output-document=zf2rapid.zip https://github.com/ZFrapid/zf2rapid/archive/master.zip
    $ unzip zf2rapid.zip 
    $ mv zf2rapid-master/ /my/zf2rapid/path/

Switch to the new path and run the following `composer` command:

    $ cd /my/zf2rapid/path
    $ composer install

Show the ZF2rapid command overview:

    $ ./bin/zf2rapid.php

## Installation on Windows with Download and Composer

Download the [ZIP file](https://github.com/ZFrapid/zf2rapid/archive/master.zip) 
and unzip it at any temporary location on your computer. Move all files to a 
dir, e.g. `c:\zf2rapid\`. 

Switch to the new path and run the following `composer` command:

    $ cd c:\zf2rapid
    $ composer install

Show the ZF2rapid command overview:

    $ bin\zf2rapid.bat

Additionally, you should add the path to `c:\zf2rapid\bin` to your `PATH` 
environment variable of your Windows configuration and reboot. After that cou 
can use ZF2rapid from any directory.

## Installation with PHAR file

tbd

