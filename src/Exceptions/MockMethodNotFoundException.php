<?php

namespace donatj\MockDuck\Exceptions;

/**
 * Exception thrown by a method mock on invoke when it does not match the spec
 * and wishes to indicate the parent should continue
 */
class MockMethodNotFoundException extends \OutOfBoundsException {

}
