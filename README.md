Commander
---------

Commander is a simple command wrapper, which makes system commands
available as static methods.

## Install

### Install via [Composer](http://packagist.org/about-composer):

1\. Add this to your `composer.json`:

    {
        "require": {
            "chh/commander": "*"
        }
    }

2\. Then run this in your project directory:

    # You can skip this if you already have composer
    wget http://getcomposer.org/composer.phar
    php composer.phar install

3\. Put this in your code:

    require "vendor/.composer/autoload.php";

## Usage

Commander makes system commands available as static methods of the
`Commander` class. When a command is called as method, then Commander
looks up the method name in your `PATH` environment variable:

    <?php
    use Commander as cmd;

    echo cmd::ifconfig();

To call commands with dashes in their name, just substitute the dashes
with underscores when calling the method. To call `apt-get` command you would
then call the `apt_get` method. Commander looks first for `apt_get` and
then when it does not find this command, then it looks again with the
underscores replaced by dashes.

    cmd::apt_get("install", "openarena");

All arguments passed to the method call get passed on to the system
command as arguments.

### Flags

To pass flags, pass them as key-value pairs as first arguemnt:

    # apt-get -y upgrade
    cmd::apt_get(array('y' => true), "upgrade");

    # rm -r -f /some/dir
    cmd::rm(array('r' => true, 'f' => true), "/some/dir");

You can pass flags as part of the argument list too:

    # git clone --branch development git://github.com/CHH/Commander
    cmd::git("clone", "--branch", "development", "git://github.com/CHH/Commander");
    
    # rm -rf /some/dir
    cmd::rm("-rf", "/some/dir");

### Weird Names or absolute paths

To call commands with their absolute path, or commands which contain
some really weird characters retrieve a command instance with the
`command()` method:

    $ffmpeg = cmd::command("/usr/local/custom-ffmpeg/bin/ffmpeg");
    $ffmpeg($movieFile);

### Piping

Piping is done by function composition:

    # Removes all "require_once" occurences and comments them out.
    cmd::xargs(
        cmd::find($sourceDir, "-print0", "-name", "*.php"), 
        "-0",
        "sed", "-E", "-i", "-e", 's/^(require_once)/\/\/ \1/g'
    );

### Errors

When a command exits with an exit status greater than Zero, then
an `Commander\ErrorException` is thrown. This exception has the code
set to the command's exit status and has a `getErrorOutput()` method
which returns everything the command wrote to `STDERR`.

## License

The MIT License

Copyright (c) 2011 Christoph Hochstrasser

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

