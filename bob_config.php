<?php

namespace Bob;

task('default', array('test'));

task('test', array('phpunit.xml'), function() {
    sh('phpunit');
});

fileTask('phpunit.xml', array('phpunit.xml.dist'), function($task) {
    copy($task->prerequisites[0], $task->name);
});

