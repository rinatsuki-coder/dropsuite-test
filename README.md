# Dropsuite Test
This repository for dropsuite software engineer test.

## About
This application can inform you about largest count of your duplicate content file in directories and subdirectories. It return that duplicate content and largest count.
For example if there are 4 files with content “abcdef” and 5 files with content “abcd” then the return value is: **abcd 5**

By default, the directories structure shown like below:
```sh
.
├── example-dir
│   ├── A
│   │   └── content2
│   ├── B
│   │   ├── content1
│   │   └── D
│   │       ├── content3
│   │       └── diff
│   └── content1
├── Application.php
├── ScannerDuplicate.php
└── README.md



```
every file has their own value.


## Running the test

This application tested on PHP Version 7 on Linux. If you want to run this just clone this repo
```sh
git clone https://github.com/rinatsuki-coder/dropsuite-test.git
```
and run on your local machine on terminal
```
php -f Application.php <path> [debug]
```
for example
```
php -f Application.php example-dir
```
or
```
php -f Application.php example-dir debug
```
